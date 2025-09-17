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
        
        if (!$user->isSeller()) {
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

    /**
     * Create appointment request to collector
     */
    public function createCollectorAppointment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fish_farm_id' => 'required|exists:fish_farms,id',
            'collector_id' => 'required|exists:collectors,id',
            'tanggal_janji' => 'required|date|after:today',
            'waktu_janji' => 'nullable|string',
            'perkiraan_berat' => 'required|numeric|min:1',
            'pesan_pemilik' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();

            // Check if fish farm belongs to user
            $fishFarm = \App\Models\FishFarm::where('id', $request->fish_farm_id)
                ->where('user_id', $user->id)
                ->first();

            if (!$fishFarm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fish farm not found or unauthorized'
                ], 404);
            }

            // Get collector to calculate estimated price
            $collector = \App\Models\Collector::findOrFail($request->collector_id);
            $estimatedPrice = $request->perkiraan_berat * $collector->rate_harga_per_kg;

            $appointment = Appointment::create([
                'fish_farm_id' => $request->fish_farm_id,
                'collector_id' => $request->collector_id,
                'user_id' => $user->id,
                'tanggal_janji' => $request->tanggal_janji,
                'tanggal' => $request->tanggal_janji,
                'waktu_janji' => $request->waktu_janji,
                'perkiraan_berat' => $request->perkiraan_berat,
                'harga_per_kg' => $collector->rate_harga_per_kg,
                'total_estimasi' => $estimatedPrice,
                'pesan_pemilik' => $request->pesan_pemilik,
                'catatan' => $request->pesan_pemilik,
                'status' => 'pending',
                'jenis' => 'penjemputan'
            ]);

            // Load relationships for response
            $appointment->load(['fishFarm', 'collector.user']);

            return response()->json([
                'success' => true,
                'message' => 'Appointment request sent successfully',
                'data' => $appointment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's appointments with collectors
     */
    public function getCollectorAppointments(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $query = Appointment::with(['fishFarm', 'collector'])
                ->where('user_id', $user->id)
                ->where('jenis', 'penjemputan');

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('date_from')) {
                $query->whereDate('tanggal_janji', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('tanggal_janji', '<=', $request->date_to);
            }

            $appointments = $query->orderBy('tanggal_janji', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel collector appointment
     */
    public function cancelCollectorAppointment($id, Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $appointment = Appointment::where('id', $id)
                ->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'diterima'])
                ->first();

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found or cannot be cancelled'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'alasan_pembatalan' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $appointment->update([
                'status' => 'dibatalkan',
                'catatan' => $request->alasan_pembatalan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Appointment cancelled successfully',
                'data' => $appointment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send WhatsApp summary for completed appointment
     */
    public function sendWhatsAppSummary($id): JsonResponse
    {
        try {
            $user = request()->user();

            $appointment = Appointment::with(['fishFarm', 'collector.user'])
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->where('status', 'selesai')
                ->first();

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Completed appointment not found'
                ], 404);
            }

            // Generate WhatsApp summary
            $summary = $this->generateWhatsAppSummary($appointment);

            // Mark as sent
            $appointment->update(['whatsapp_sent' => true]);

            return response()->json([
                'success' => true,
                'message' => 'WhatsApp summary generated successfully',
                'data' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate WhatsApp summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate WhatsApp summary message
     */
    private function generateWhatsAppSummary($appointment)
    {
        $fishFarmName = $appointment->fishFarm->nama ?? 'Tambak';
        $ownerName = $appointment->fishFarm->user->name ?? 'Pemilik';
        $collectorName = $appointment->collector->nama ?? 'Pengepul';
        $collectorPhone = $appointment->collector->user->phone ?? '';
        
        $message = "📋 *SUMMARY PENJEMPUTAN IKAN* 📋\n\n";
        $message .= "🏪 *Tambak:* {$fishFarmName}\n";
        $message .= "👤 *Pemilik:* {$ownerName}\n";
        $message .= "🚛 *Pengepul:* {$collectorName}\n\n";
        $message .= "📅 *Tanggal:* " . $appointment->tanggal_janji->format('d/m/Y') . "\n";
        
        if ($appointment->berat_aktual) {
            $message .= "⚖️ *Berat Aktual:* " . number_format($appointment->berat_aktual, 2) . " kg\n";
        }
        if ($appointment->kualitas_ikan) {
            $message .= "🏷️ *Kualitas:* {$appointment->kualitas_ikan}\n";
        }
        if ($appointment->harga_final) {
            $message .= "💰 *Harga per Kg:* Rp " . number_format($appointment->harga_final, 0, ',', '.') . "\n";
        }
        if ($appointment->total_final) {
            $message .= "💵 *Total Pembayaran:* Rp " . number_format($appointment->total_final, 0, ',', '.') . "\n";
        }
        
        $message .= "\n✅ *Status:* SELESAI\n\n";
        $message .= "_Terima kasih atas kerjasamanya!_\n";
        $message .= "*IwakMart - Connecting Fish Farmers*";

        // Generate WhatsApp URL
        if ($collectorPhone) {
            $whatsappUrl = "https://wa.me/{$collectorPhone}?text=" . urlencode($message);
            return [
                'message' => $message,
                'whatsapp_url' => $whatsappUrl,
                'phone_number' => $collectorPhone
            ];
        }

        return [
            'message' => $message,
            'whatsapp_url' => null,
            'phone_number' => null
        ];
    }
}