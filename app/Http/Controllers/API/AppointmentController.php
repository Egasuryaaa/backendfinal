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
use App\Services\WhatsAppService;

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
        
        // Use new field names based on user role
        $query = Appointment::with(['pemilikTambak', 'fishFarm', 'collector.user']);
        
        if ($user->isPemilikTambak()) {
            // Pemilik tambak sees appointments they created
            $query->where('user_id', $user->id);
        } elseif ($user->isPengepul()) {
            // Pengepul sees appointments directed to their collectors
            $collectorIds = \App\Models\Collector::where('user_id', $user->id)->pluck('id');
            $query->whereIn('collector_id', $collectorIds);
        }
        
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
        $sortDir = $request->sort_dir ?? 'desc'; // Changed to desc for newer first
        
        if ($sortBy === 'tanggal_janji') {
            $query->orderBy($sortBy, $sortDir);
        }
        
        // Paginasi
        $perPage = $request->per_page ?? 10;
        $appointments = $query->paginate($perPage);
        
        // Format untuk tampilan
        $appointments->getCollection()->transform(function ($item) {
            if ($item->tanggal_janji) {
                $item->formatted_date = Carbon::parse($item->tanggal_janji)->format('d M Y');
                $item->formatted_time = Carbon::parse($item->tanggal_janji)->format('H:i');
            }
            $item->status_text = Appointment::$statuses[$item->status] ?? $item->status;
            
            // Clean the data by removing unused fields
            return (object) $this->cleanAppointmentData($item);
        });

        return response()->json([
            'success' => true,
            'data' => $appointments,
            'user_role' => $user->isPemilikTambak() ? 'pemilik_tambak' : ($user->isPengepul() ? 'pengepul' : 'other')
        ]);
    }

    /**
     * DEPRECATED: Legacy store method
     * Use createCollectorAppointment instead
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'This endpoint is deprecated. Use /api/appointments/collector instead.'
        ], 410);
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
        
        // Check authorization using new field structure
        $isAuthorized = false;
        
        if ($user->isPemilikTambak() && $appointment->user_id === $user->id) {
            $isAuthorized = true;
        } elseif ($user->isPengepul()) {
            $collectorIds = \App\Models\Collector::where('user_id', $user->id)->pluck('id');
            if ($collectorIds->contains($appointment->collector_id)) {
                $isAuthorized = true;
            }
        }
        
        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $appointment->load(['pemilikTambak', 'fishFarm', 'collector.user', 'messages']);
        
        // Prepare clean response data with formatted values
        $responseData = $this->cleanAppointmentData($appointment);
        if ($appointment->tanggal_janji) {
            $responseData['formatted_date'] = Carbon::parse($appointment->tanggal_janji)->format('d M Y');
            $responseData['formatted_time'] = Carbon::parse($appointment->tanggal_janji)->format('H:i');
        }
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
        
        // Only appointment creator (pemilik tambak) can update appointment
        if ($appointment->user_id !== $user->id) {
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
            'type' => 'janji_temu',
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
        
        // Only appointment creator (pemilik tambak) can delete appointment
        if ($appointment->user_id !== $user->id) {
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
                'type' => 'janji_temu',
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
        
        // Validasi otorisasi berdasarkan role baru
        $isPemilikTambak = $user->isPemilikTambak() && $appointment->user_id === $user->id;
        $isPengepul = $user->isPengepul() && \App\Models\Collector::where('user_id', $user->id)
                                                  ->where('id', $appointment->collector_id)
                                                  ->exists();
        
        if ($isPemilikTambak) {
            // Pemilik tambak dapat mengubah status menjadi: dibatalkan
            if (!in_array($newStatus, ['dibatalkan'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemilik tambak hanya dapat membatalkan appointment'
                ], 400);
            }
        } elseif ($isPengepul) {
            // Pengepul dapat mengubah status menjadi: dikonfirmasi, selesai, dibatalkan
            if (!in_array($newStatus, ['dikonfirmasi', 'selesai', 'dibatalkan'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengepul dapat mengubah status menjadi dikonfirmasi, selesai, atau dibatalkan'
                ], 400);
            }
            
            // Pengepul hanya dapat menyelesaikan janji temu yang sudah dikonfirmasi
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
        $appointment->status = $newStatus;
        $appointment->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Status janji temu berhasil diperbarui',
            'data' => [
                'appointment' => $appointment,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'status_text' => Appointment::$statuses[$newStatus] ?? $newStatus
            ]
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
            'perkiraan_berat' => 'required|numeric|min:1', // Keep this for validation (maps to estimated_weight)
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

            // Validate user is pemilik tambak
            if (!$user->isPemilikTambak()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only fish farm owners (pemilik tambak) can create appointments with collectors'
                ], 403);
            }

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

            // Get collector and validate it belongs to a pengepul user
            $collector = \App\Models\Collector::with('user')->findOrFail($request->collector_id);
            
            if (!$collector->user || !$collector->user->isPengepul()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected collector is not associated with a valid pengepul user'
                ], 400);
            }

            $estimatedPrice = $request->perkiraan_berat * ($collector->rate_harga_per_kg ?? 0);

            $appointmentData = [
                'user_id' => $user->id, // Use user_id for pemilik tambak
                'fish_farm_id' => $request->fish_farm_id,
                'collector_id' => $request->collector_id,
                'tanggal_janji' => $request->tanggal_janji,
                'waktu_janji' => $request->waktu_janji,
                'estimated_weight' => $request->perkiraan_berat, // Map to correct field
                'price_per_kg' => $collector->rate_harga_per_kg ?? 0, // Map to correct field
                'pesan_pemilik' => $request->pesan_pemilik,
                'catatan' => $request->catatan ?? $request->pesan_pemilik,
                'tujuan' => $request->tujuan ?? 'Pengepulan Ikan',
                'status' => 'menunggu', // Use correct enum value
                'appointment_type' => 'pengepulan_ikan' // Use correct field and enum value
            ];

            \Log::info('Creating appointment with data:', $appointmentData);

            $appointment = Appointment::create($appointmentData);

            // Load relationships for response
            $appointment->load(['fishFarm', 'collector.user', 'pemilikTambak']);

            // Send WhatsApp notification to collector
            try {
                $whatsAppService = new WhatsAppService();
                $notificationResult = $whatsAppService->sendFishFarmAppointmentSummary($appointment);
                
                \Log::info('WhatsApp notification sent for appointment', [
                    'appointment_id' => $appointment->id,
                    'result' => $notificationResult
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send WhatsApp notification for appointment', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the appointment creation if WhatsApp fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment request sent successfully to collector',
                'data' => [
                    'appointment' => $this->cleanAppointmentData($appointment),
                    'fish_farm' => $appointment->fishFarm,
                    'collector' => $appointment->collector,
                    'collector_user' => $appointment->collector->user,
                    'pemilik_tambak' => $appointment->pemilikTambak
                ]
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Appointment creation error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create appointment',
                'error' => $e->getMessage(),
                'debug' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Get appointments based on user role (pemilik_tambak or pengepul)
     */
    public function getCollectorAppointments(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user->isPemilikTambak()) {
                // Pemilik tambak melihat appointments yang mereka buat
                $query = Appointment::with(['fishFarm', 'collector.user'])
                    ->where('user_id', $user->id)
                    ->where('appointment_type', 'pengepulan_ikan');
            } elseif ($user->isPengepul()) {
                // Pengepul melihat appointments yang ditujukan untuk mereka
                $collectorIds = \App\Models\Collector::where('user_id', $user->id)->pluck('id');
                
                // If user has no collector businesses, return empty result instead of error
                if ($collectorIds->isEmpty()) {
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'data' => [],
                            'current_page' => 1,
                            'last_page' => 1,
                            'per_page' => 15,
                            'total' => 0
                        ],
                        'user_role' => 'pengepul',
                        'message' => 'No collector businesses found. Please register a collector business first.'
                    ]);
                }
                
                $query = Appointment::with(['fishFarm', 'collector.user', 'pemilikTambak'])
                    ->whereIn('collector_id', $collectorIds)
                    ->where('appointment_type', 'pengepulan_ikan');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pemilik tambak and pengepul can access collector appointments'
                ], 403);
            }

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

            // Clean appointment data in pagination results
            $appointments->getCollection()->transform(function ($appointment) {
                $cleanData = $this->cleanAppointmentData($appointment);
                if ($appointment->tanggal_janji) {
                    $cleanData['formatted_date'] = Carbon::parse($appointment->tanggal_janji)->format('d M Y');
                    $cleanData['formatted_time'] = Carbon::parse($appointment->tanggal_janji)->format('H:i');
                }
                $cleanData['status_text'] = Appointment::$statuses[$appointment->status] ?? $appointment->status;
                return (object) $cleanData;
            });

            return response()->json([
                'success' => true,
                'data' => $appointments,
                'user_role' => $user->isPemilikTambak() ? 'pemilik_tambak' : 'pengepul'
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
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
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
     * Pengepul responds to appointment (accept/reject)
     */
    public function respondToAppointment($id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:dikonfirmasi,dibatalkan',
            'catatan' => 'nullable|string|max:500'
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

            // Validate user is pengepul
            if (!$user->isPengepul()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pengepul can respond to appointments'
                ], 403);
            }

            // Get user's collector IDs
            $collectorIds = \App\Models\Collector::where('user_id', $user->id)->pluck('id');

            // First check if appointment exists at all
            $appointmentExists = Appointment::where('id', $id)->first();
            if (!$appointmentExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            // Check if appointment belongs to user's collectors
            if (!$collectorIds->contains($appointmentExists->collector_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This appointment does not belong to your collectors'
                ], 403);
            }

            // Check if appointment status is correct for the action
            if ($request->status === 'dikonfirmasi' && $appointmentExists->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot confirm appointment with status '{$appointmentExists->status}', can only confirm 'menunggu' appointments"
                ], 422);
            }
            
            if ($request->status === 'dibatalkan' && !in_array($appointmentExists->status, ['menunggu', 'dikonfirmasi'])) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot cancel appointment with status '{$appointmentExists->status}', can only cancel 'menunggu' or 'dikonfirmasi' appointments"
                ], 422);
            }

            $appointment = $appointmentExists;

            $oldStatus = $appointment->status;
            
            $appointment->update([
                'status' => $request->status,
                'catatan' => $request->catatan
            ]);

            // Load relationships for response
            $appointment->load(['fishFarm', 'collector.user', 'pemilikTambak']);

            // Send WhatsApp notification about status update
            try {
                $whatsAppService = new WhatsAppService();
                $notificationResult = $whatsAppService->sendAppointmentStatusUpdate($appointment, $oldStatus, $request->status);
                
                \Log::info('WhatsApp status update notification sent', [
                    'appointment_id' => $appointment->id,
                    'old_status' => $oldStatus,
                    'new_status' => $request->status,
                    'result' => $notificationResult
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send WhatsApp status update notification', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the status update if WhatsApp fails
            }

            return response()->json([
                'success' => true,
                'message' => $request->status === 'dikonfirmasi' ? 'Appointment confirmed successfully' : 'Appointment rejected',
                'data' => $appointment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to respond to appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete appointment (for pengepul)
     */
    public function completeAppointment($id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'berat_aktual' => 'required|numeric|min:0.1',
            'kualitas_ikan' => 'nullable|string|max:255',
            'catatan_selesai' => 'nullable|string|max:500'
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

            // Validate user is pengepul
            if (!$user->isPengepul()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pengepul can complete appointments'
                ], 403);
            }

            // Get user's collector IDs
            $collectorIds = \App\Models\Collector::where('user_id', $user->id)->pluck('id');

            // First check if appointment exists at all
            $appointmentExists = Appointment::where('id', $id)->first();
            if (!$appointmentExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            // Check if appointment belongs to user's collectors
            if (!$collectorIds->contains($appointmentExists->collector_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This appointment does not belong to your collectors'
                ], 403);
            }

            // Check if appointment status is correct
            if ($appointmentExists->status !== 'dikonfirmasi') {
                return response()->json([
                    'success' => false,
                    'message' => "Appointment status is '{$appointmentExists->status}', can only complete 'dikonfirmasi' appointments"
                ], 422);
            }

            $appointment = $appointmentExists;

            // Calculate final totals
            $totalAktual = $request->berat_aktual * $appointment->harga_per_kg;

            $appointment->update([
                'status' => 'selesai',
                'berat_aktual' => $request->berat_aktual,
                'total_aktual' => $totalAktual,
                'kualitas_ikan' => $request->kualitas_ikan,
                'catatan_selesai' => $request->catatan_selesai,
                'tanggal_selesai' => now()
            ]);

            // Load relationships for response
            $appointment->load(['fishFarm', 'collector.user', 'pemilikTambak']);

            // Send WhatsApp notification about completion
            try {
                $whatsAppService = new WhatsAppService();
                $notificationResult = $whatsAppService->sendAppointmentCompletion($appointment);
                
                \Log::info('WhatsApp completion notification sent', [
                    'appointment_id' => $appointment->id,
                    'result' => $notificationResult
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send WhatsApp completion notification', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the completion if WhatsApp fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment completed successfully',
                'data' => $appointment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete appointment',
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

    /**
     * Clean appointment data by removing empty optional fields
     */
    private function cleanAppointmentData($appointment)
    {
        $cleanData = $appointment->toArray();
        
        // Only include these if they have values (optional cleanup)
        if (empty($cleanData['whatsapp_summary'])) {
            unset($cleanData['whatsapp_summary']);
        }
        if (empty($cleanData['whatsapp_sent_at'])) {
            unset($cleanData['whatsapp_sent_at']);
        }
        
        return $cleanData;
    }
}