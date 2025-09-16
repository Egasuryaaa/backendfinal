<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Collector;
use App\Models\FishFarm;
use App\Models\Appointment;
use App\Models\User;
use App\Services\WhatsAppService;
use App\Services\LocationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CollectorController extends Controller
{
    use ApiResponse;

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display a listing of collectors
     */
    public function index(Request $request)
    {
        try {
            $query = Collector::with('user');

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by fish type acceptance
            if ($request->has('fish_type')) {
                $query->whereJsonContains('jenis_ikan_diterima', $request->fish_type);
            }

            // Filter by user (for own collector business)
            if ($request->has('user_only') && $request->user_only == 'true') {
                $query->where('user_id', Auth::id());
            }

            $collectors = $query->paginate($request->get('per_page', 10));

            return $this->success($collectors, 'Collectors retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve collectors', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created collector business
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Only allow pengepul to create collector businesses
            if (!$user->isPengepul() && !$user->isAdmin()) {
                return $this->error('Unauthorized - Only collectors can create collector businesses', 403);
            }

            $validator = Validator::make($request->all(), [
                'nama_usaha' => 'required|string|max:255',
                'lokasi_koordinat' => 'required|array',
                'lokasi_koordinat.lat' => 'required|numeric|between:-90,90',
                'lokasi_koordinat.lng' => 'required|numeric|between:-180,180',
                'alamat' => 'required|string',
                'no_telepon' => 'required|string|max:20',
                'jenis_ikan_diterima' => 'required|array',
                'jenis_ikan_diterima.*' => 'string|max:255',
                'rate_per_kg' => 'required|numeric|min:0',
                'kapasitas_maximum' => 'required|numeric|min:1',
                'jam_operasional' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'deskripsi' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }

            $data = $validator->validated();
            $data['user_id'] = Auth::id();
            $data['status'] = 'aktif';

            // Handle photo upload
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('collectors', 'public');
            }

            $collector = Collector::create($data);
            $collector->load('user');

            return $this->success($collector, 'Collector business created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create collector business', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified collector
     */
    public function show($id)
    {
        try {
            $collector = Collector::with('user', 'appointments.fishFarm')->findOrFail($id);
            
            return $this->success($collector, 'Collector retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Collector not found', 404);
        }
    }

    /**
     * Update the specified collector
     */
    public function update(Request $request, $id)
    {
        try {
            $collector = Collector::findOrFail($id);

            // Check ownership
            if ($collector->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            $validator = Validator::make($request->all(), [
                'nama_usaha' => 'sometimes|string|max:255',
                'lokasi_koordinat' => 'sometimes|array',
                'lokasi_koordinat.lat' => 'required_with:lokasi_koordinat|numeric|between:-90,90',
                'lokasi_koordinat.lng' => 'required_with:lokasi_koordinat|numeric|between:-180,180',
                'alamat' => 'sometimes|string',
                'no_telepon' => 'sometimes|string|max:20',
                'jenis_ikan_diterima' => 'sometimes|array',
                'jenis_ikan_diterima.*' => 'string|max:255',
                'rate_per_kg' => 'sometimes|numeric|min:0',
                'kapasitas_maximum' => 'sometimes|numeric|min:1',
                'jam_operasional' => 'sometimes|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'sometimes|in:aktif,tidak_aktif',
                'deskripsi' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }

            $data = $validator->validated();

            // Handle photo upload
            if ($request->hasFile('foto')) {
                // Delete old photo
                if ($collector->foto) {
                    Storage::disk('public')->delete($collector->foto);
                }
                $data['foto'] = $request->file('foto')->store('collectors', 'public');
            }

            $collector->update($data);
            $collector->load('user');

            return $this->success($collector, 'Collector updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update collector', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified collector
     */
    public function destroy($id)
    {
        try {
            $collector = Collector::findOrFail($id);

            // Check ownership
            if ($collector->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            // Delete photo if exists
            if ($collector->foto) {
                Storage::disk('public')->delete($collector->foto);
            }

            $collector->delete();

            return $this->success(null, 'Collector deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete collector', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get nearby fish farms for this collector
     */
    public function getNearbyFishFarms($id, Request $request)
    {
        try {
            $collector = Collector::findOrFail($id);
            
            // Check ownership
            if ($collector->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            $maxDistance = $request->get('max_distance', 50);
            $fishFarms = $collector->getNearbyFishFarms($maxDistance);

            // Add earnings calculation for each fish farm
            $fishFarmsWithEarnings = $fishFarms->map(function ($fishFarm) use ($collector) {
                $fishFarm->potential_earnings = $collector->calculateEarnings($fishFarm);
                $fishFarm->accepts_fish_type = $collector->acceptsFishType($fishFarm->jenis_ikan);
                $fishFarm->estimated_production = $fishFarm->estimated_production;
                return $fishFarm;
            });

            return $this->success($fishFarmsWithEarnings, 'Nearby fish farms retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve fish farms', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get pending appointments for collector
     */
    public function getPendingAppointments($id)
    {
        try {
            $collector = Collector::findOrFail($id);
            
            // Check ownership
            if ($collector->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            $appointments = Appointment::with(['fishFarm', 'user'])
                ->where('collector_id', $collector->id)
                ->where('status', 'pending')
                ->orderBy('tanggal', 'asc')
                ->get();

            return $this->success($appointments, 'Pending appointments retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve appointments', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Accept or reject appointment
     */
    public function handleAppointment(Request $request, $id, $appointmentId)
    {
        try {
            $collector = Collector::findOrFail($id);
            
            // Check ownership
            if ($collector->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            $appointment = Appointment::findOrFail($appointmentId);

            if ($appointment->collector_id !== $collector->id) {
                return $this->error('Appointment does not belong to this collector', 403);
            }

            $validator = Validator::make($request->all(), [
                'action' => 'required|in:accept,reject',
                'catatan_collector' => 'nullable|string',
                'harga_final' => 'required_if:action,accept|nullable|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }

            if ($request->action === 'accept') {
                $oldStatus = $appointment->status;
                $appointment->update([
                    'status' => 'diterima',
                    'catatan_collector' => $request->catatan_collector,
                    'harga_final' => $request->harga_final,
                    'total_final' => $request->harga_final * $appointment->perkiraan_berat
                ]);
                
                // Send WhatsApp notification for status update
                $this->whatsappService->sendAppointmentStatusUpdate($appointment, $oldStatus, 'diterima');
            } else {
                $oldStatus = $appointment->status;
                $appointment->update([
                    'status' => 'ditolak',
                    'catatan_collector' => $request->catatan_collector
                ]);
                
                // Send WhatsApp notification for status update
                $this->whatsappService->sendAppointmentStatusUpdate($appointment, $oldStatus, 'ditolak');
            }

            $appointment->load(['fishFarm', 'collector']);

            return $this->success($appointment, 'Appointment ' . $request->action . 'ed successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to handle appointment', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Complete appointment and mark as finished
     */
    public function completeAppointment(Request $request, $id, $appointmentId)
    {
        try {
            $collector = Collector::findOrFail($id);
            
            // Check ownership
            if ($collector->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            $appointment = Appointment::findOrFail($appointmentId);

            if ($appointment->collector_id !== $collector->id) {
                return $this->error('Appointment does not belong to this collector', 403);
            }

            if ($appointment->status !== 'diterima') {
                return $this->error('Appointment must be accepted before completion', 400);
            }

            $validator = Validator::make($request->all(), [
                'berat_aktual' => 'required|numeric|min:0',
                'kualitas_ikan' => 'required|string|max:255',
                'catatan_selesai' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }

            $appointment->update([
                'status' => 'selesai',
                'berat_aktual' => $request->berat_aktual,
                'kualitas_ikan' => $request->kualitas_ikan,
                'catatan_selesai' => $request->catatan_selesai,
                'total_aktual' => $request->berat_aktual * $appointment->harga_final,
                'tanggal_selesai' => now()
            ]);

            // Send WhatsApp completion notification
            $this->whatsappService->sendAppointmentCompletion($appointment);

            $appointment->load(['fishFarm', 'collector']);

            return $this->success($appointment, 'Appointment completed successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to complete appointment', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get nearest collectors for fish farm owners
     */
    public function getNearestCollectors(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Check if user is authenticated
            if (!$user) {
                return $this->error('User not authenticated', 401);
            }

            // Only allow pemilik_tambak to access this endpoint
            if (!$user->isPemilikTambak() && !$user->isAdmin()) {
                return $this->error('Unauthorized - Only fish farm owners can access this endpoint', 403);
            }

            // Validate user has coordinates
            if (!$user->hasCoordinates()) {
                return $this->error('Your location coordinates are required to find nearest collectors', 400);
            }

            $validator = Validator::make($request->all(), [
                'max_distance' => 'nullable|numeric|min:1|max:500',
                'fish_type' => 'nullable|string',
                'min_rate' => 'nullable|numeric|min:0',
                'max_rate' => 'nullable|numeric|min:0',
                'min_capacity' => 'nullable|numeric|min:1',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }

            $maxDistance = $request->get('max_distance', 50); // Default 50km
            $perPage = $request->get('per_page', 10);

            // Get collectors with users that have coordinates
            $collectorsQuery = Collector::with('user')
                ->whereHas('user', function ($query) {
                    $query->whereNotNull('latitude')
                          ->whereNotNull('longitude');
                })
                ->where('status', 'aktif');

            // Filter by fish type if specified
            if ($request->has('fish_type') && $request->fish_type) {
                $collectorsQuery->whereJsonContains('jenis_ikan_diterima', $request->fish_type);
            }

            // Filter by rate range
            if ($request->has('min_rate') && $request->min_rate) {
                $collectorsQuery->where('rate_per_kg', '>=', $request->min_rate);
            }
            if ($request->has('max_rate') && $request->max_rate) {
                $collectorsQuery->where('rate_per_kg', '<=', $request->max_rate);
            }

            // Filter by capacity
            if ($request->has('min_capacity') && $request->min_capacity) {
                $collectorsQuery->where('kapasitas_maximum', '>=', $request->min_capacity);
            }

            $collectors = $collectorsQuery->get();

            // If no collectors found, return empty results
            if ($collectors->isEmpty()) {
                return $this->success([
                    'data' => [],
                    'pagination' => [
                        'current_page' => 1,
                        'per_page' => $perPage,
                        'total' => 0,
                        'last_page' => 1,
                        'from' => 0,
                        'to' => 0
                    ],
                    'user_location' => $user->getCoordinates(),
                    'search_radius' => $maxDistance . ' km'
                ], 'No collectors found matching your criteria');
            }

            // Calculate distances and filter by max distance
            $nearbyCollectors = collect();
            
            foreach ($collectors as $collector) {
                if ($collector->user && $collector->user->hasCoordinates()) {
                    $distance = LocationService::calculateDistanceBetweenUsers($user, $collector->user);
                    
                    if ($distance !== null && $distance <= $maxDistance) {
                        $collector->distance = $distance;
                        $collector->distance_formatted = LocationService::formatDistance($distance);
                        $nearbyCollectors->push($collector);
                    }
                }
            }

            // Sort by distance
            $nearbyCollectors = $nearbyCollectors->sortBy('distance')->values();

            // Paginate results manually
            $currentPage = $request->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            $paginatedCollectors = $nearbyCollectors->slice($offset, $perPage)->values();

            $pagination = [
                'current_page' => (int) $currentPage,
                'per_page' => (int) $perPage,
                'total' => $nearbyCollectors->count(),
                'last_page' => ceil($nearbyCollectors->count() / $perPage) ?: 1,
                'from' => $nearbyCollectors->count() > 0 ? $offset + 1 : 0,
                'to' => min($offset + $perPage, $nearbyCollectors->count())
            ];

            return $this->success([
                'data' => $paginatedCollectors,
                'pagination' => $pagination,
                'user_location' => $user->getCoordinates(),
                'search_radius' => $maxDistance . ' km'
            ], 'Nearest collectors retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Error in getNearestCollectors: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->error('Failed to retrieve nearest collectors', 500, [
                'error' => $e->getMessage(),
                'debug' => config('app.debug') ? $e->getTraceAsString() : null
            ]);
        }
    }
}
