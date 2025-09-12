<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use App\Models\SellerLocation;
use App\Models\User;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Mendapatkan daftar janji temu untuk pengguna yang login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = Appointment::with(['seller', 'buyer', 'sellerLocation'])
                           ->where(function($q) use ($user) {
                               $q->where('pembeli_id', $user->id)
                                 ->orWhere('penjual_id', $user->id);
                           });
        
        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal (akan datang atau sudah lewat)
        if ($request->has('upcoming') && $request->boolean('upcoming')) {
            $query->where('tanggal_janji', '>=', Carbon::now())
                  ->whereIn('status', ['menunggu', 'dikonfirmasi']);
        } elseif ($request->has('past') && $request->boolean('past')) {
            $query->where(function($q) {
                $q->where('tanggal_janji', '<', Carbon::now())
                  ->orWhereIn('status', ['selesai', 'dibatalkan']);
            });
        }
        
        // Pengurutan
        $sortBy = $request->sort_by ?? 'tanggal_janji';
        $sortDir = $request->sort_dir ?? 'asc';
        
        if ($sortBy === 'tanggal_janji') {
            $query->orderBy($sortBy, $sortDir);
        }
        
        // Paginasi
        $perPage = $request->per_page ?? 10;
        $appointments = $query->paginate($perPage);
        
        // Format untuk tampilan
        $appointments->getCollection()->transform(function ($item) {
            $item->formatted_date = Carbon::parse($item->tanggal_janji)->format('d M Y');
            $item->formatted_time = Carbon::parse($item->tanggal_janji)->format('H:i');
            $item->status_text = Appointment::$statuses[$item->status] ?? $item->status;
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /**
     * Create a new appointment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required|exists:users,id',
            'location_id' => 'required|exists:seller_locations,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get current authenticated user
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login untuk membuat janji temu'
                ], 401);
            }

            // Create appointment date time
            $appointmentDateTime = $request->date . ' ' . $request->time;
            
            // Create the appointment
            $appointment = Appointment::create([
                'pembeli_id' => $user->id,
                'penjual_id' => $request->seller_id,
                'lokasi_penjual_id' => $request->location_id,
                'tanggal_janji' => $appointmentDateTime,
                'tujuan' => $request->purpose,
                'catatan' => $request->notes,
                'status' => 'menunggu',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Janji temu berhasil dibuat',
                'data' => $appointment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat janji temu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail janji temu.
     *
     * @param  \App\Models\Appointment  $appointment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Appointment $appointment, Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Pastikan janji temu terkait dengan pengguna
        if ($appointment->pembeli_id !== $user->id && $appointment->penjual_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $appointment->load(['seller', 'buyer', 'sellerLocation', 'messages']);
        
        // Prepare response data with formatted values
        $responseData = $appointment->toArray();
        $responseData['formatted_date'] = Carbon::parse($appointment->tanggal_janji)->format('d M Y');
        $responseData['formatted_time'] = Carbon::parse($appointment->tanggal_janji)->format('H:i');
        $responseData['status_text'] = Appointment::$statuses[$appointment->status] ?? $appointment->status;

        return response()->json([
            'success' => true,
            'data' => $responseData
        ]);
    }

    /**
     * Memperbarui janji temu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        $user = $request->user();
        
        // Hanya pembuat janji temu (pembeli) yang dapat mengubah janji
        if ($appointment->pembeli_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        // Periksa apakah janji temu masih bisa diubah
        if (in_array($appointment->status, ['selesai', 'dibatalkan'])) {
            return response()->json([
                'success' => false,
                'message' => 'Janji temu tidak dapat diubah'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'tanggal_janji' => 'sometimes|date|after:now',
            'tujuan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Jika ada perubahan tanggal, validasi ulang jam operasional
        if ($request->has('tanggal_janji')) {
            $location = $appointment->sellerLocation;
            
            // Verifikasi waktu (jam operasional)
            $appointmentDateTime = Carbon::parse($request->tanggal_janji);
            $appointmentDayName = $appointmentDateTime->translatedFormat('l');
            $appointmentTime = $appointmentDateTime->format('H:i:s');
            
            // Konversi nama hari ke bahasa Indonesia
            $dayMapping = [
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu',
            ];
            
            $dayName = $dayMapping[$appointmentDayName] ?? $appointmentDayName;
            
            // Cek jam operasional
            $operatingHours = collect($location->jam_operasional ?? []);
            $daySchedule = $operatingHours->firstWhere('hari', $dayName);
            
            if (!$daySchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lokasi tidak beroperasi pada hari ' . $dayName
                ], 400);
            }
            
            if (isset($daySchedule['jam_buka']) && isset($daySchedule['jam_tutup'])) {
                $openTime = Carbon::parse($daySchedule['jam_buka']);
                $closeTime = Carbon::parse($daySchedule['jam_tutup']);
                $appointmentTimeObj = Carbon::parse($appointmentTime);
                
                if ($appointmentTimeObj->lt($openTime) || $appointmentTimeObj->gt($closeTime)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Waktu di luar jam operasional (' . $daySchedule['jam_buka'] . ' - ' . $daySchedule['jam_tutup'] . ')'
                    ], 400);
                }
            }
            
            // Reset status menjadi menunggu jika tanggal berubah
            $appointment->status = 'menunggu';
        }
        
        // Update data janji temu
        $appointment->fill($request->only(['tanggal_janji', 'tujuan', 'catatan']));
        $appointment->save();
        
        // Buat notifikasi untuk penjual
        $appointment->seller->notifications()->create([
            'judul' => 'Janji Temu Diperbarui',
            'isi' => "{$user->name} telah memperbarui janji temu.",
            'jenis' => 'janji_temu',
            'janji_temu_id' => $appointment->id,
            'tautan' => '/janji-temu/' . $appointment->id,
        ]);
        
        $appointment->load(['seller', 'buyer', 'sellerLocation']);
        
        // Prepare response data with formatted values
        $responseData = $appointment->toArray();
        $responseData['formatted_date'] = Carbon::parse($appointment->tanggal_janji)->format('d M Y');
        $responseData['formatted_time'] = Carbon::parse($appointment->tanggal_janji)->format('H:i');
        $responseData['status_text'] = Appointment::$statuses[$appointment->status] ?? $appointment->status;

        return response()->json([
            'success' => true,
            'message' => 'Janji temu berhasil diperbarui',
            'data' => $responseData
        ]);
    }

    /**
     * Menghapus janji temu.
     *
     * @param  \App\Models\Appointment  $appointment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Appointment $appointment, Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Hanya pembuat janji temu (pembeli) yang dapat menghapus janji
        if ($appointment->pembeli_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        // Periksa apakah janji temu masih bisa dihapus (hanya yang masih menunggu)
        if ($appointment->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya janji temu dengan status menunggu yang dapat dihapus'
            ], 400);
        }
        
        // Simpan data untuk notifikasi
        $sellerId = $appointment->penjual_id;
        $seller = $appointment->seller;
        
        // Hapus janji temu
        $appointment->delete();
        
        // Buat notifikasi untuk penjual
        if ($seller) {
            $seller->notifications()->create([
                'judul' => 'Janji Temu Dibatalkan',
                'isi' => "{$user->name} telah membatalkan janji temu.",
                'jenis' => 'janji_temu',
                'tautan' => '/janji-temu',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Janji temu berhasil dihapus'
        ]);
    }
    
    /**
     * Memperbarui status janji temu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, Appointment $appointment): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,dikonfirmasi,selesai,dibatalkan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = $request->user();
        $newStatus = $request->status;
        
        // Validasi otorisasi berdasarkan status dan peran
        if ($appointment->penjual_id === $user->id) {
            // Penjual dapat mengubah status menjadi: dikonfirmasi, selesai, dibatalkan
            if (!in_array($newStatus, ['dikonfirmasi', 'selesai', 'dibatalkan'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penjual hanya dapat mengubah status menjadi dikonfirmasi, selesai, atau dibatalkan'
                ], 400);
            }
        } elseif ($appointment->pembeli_id === $user->id) {
            // Pembeli hanya dapat mengubah status menjadi: dibatalkan, selesai
            if (!in_array($newStatus, ['dibatalkan', 'selesai'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembeli hanya dapat mengubah status menjadi dibatalkan atau selesai'
                ], 400);
            }
            
            // Pembeli hanya dapat menyelesaikan janji temu yang sudah dikonfirmasi
            if ($newStatus === 'selesai' && $appointment->status !== 'dikonfirmasi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya janji temu yang sudah dikonfirmasi yang dapat diselesaikan'
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        // Update status
        $oldStatus = $appointment->status;
        $appointment->updateStatus($newStatus);
        
        // Prepare response data with formatted values
        $appointment->load(['seller', 'buyer', 'sellerLocation']);
        $responseData = $appointment->toArray();
        $responseData['formatted_date'] = Carbon::parse($appointment->tanggal_janji)->format('d M Y');
        $responseData['formatted_time'] = Carbon::parse($appointment->tanggal_janji)->format('H:i');
        $responseData['status_text'] = Appointment::$statuses[$appointment->status] ?? $appointment->status;

        return response()->json([
            'success' => true,
            'message' => 'Status janji temu berhasil diperbarui',
            'data' => $responseData
        ]);
    }
    
    /**
     * Mendapatkan daftar janji temu untuk penjual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sellerAppointments(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $query = Appointment::with(['buyer', 'sellerLocation'])
                           ->where('penjual_id', $user->id);
        
        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan lokasi
        if ($request->has('lokasi_id')) {
            $query->where('lokasi_penjual_id', $request->lokasi_id);
        }
        
        // Filter berdasarkan tanggal (akan datang atau sudah lewat)
        if ($request->has('upcoming') && $request->boolean('upcoming')) {
            $query->where('tanggal_janji', '>=', Carbon::now())
                  ->whereIn('status', ['menunggu', 'dikonfirmasi']);
        } elseif ($request->has('past') && $request->boolean('past')) {
            $query->where(function($q) {
                $q->where('tanggal_janji', '<', Carbon::now())
                  ->orWhereIn('status', ['selesai', 'dibatalkan']);
            });
        }
        
        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            
            $query->whereBetween('tanggal_janji', [$startDate, $endDate]);
        }
        
        // Pengurutan
        $sortBy = $request->sort_by ?? 'tanggal_janji';
        $sortDir = $request->sort_dir ?? 'asc';
        
        if ($sortBy === 'tanggal_janji') {
            $query->orderBy($sortBy, $sortDir);
        }
        
        // Paginasi
        $perPage = $request->per_page ?? 10;
        $appointments = $query->paginate($perPage);
        
        // Format untuk tampilan
        $appointments->getCollection()->transform(function ($item) {
            $item->formatted_date = Carbon::parse($item->tanggal_janji)->format('d M Y');
            $item->formatted_time = Carbon::parse($item->tanggal_janji)->format('H:i');
            $item->status_text = Appointment::$statuses[$item->status] ?? $item->status;
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }
}