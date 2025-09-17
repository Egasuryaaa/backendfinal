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
            
            // Auto-assign pengepul role if user doesn't have it
            if (!$user->isPengepul() && !$user->isAdmin()) {
                // Update user role to pengepul
                $user->role = 'pengepul';
                $user->save();
                
                // Log the role change
                \Log::info("User {$user->id} role updated to 'pengepul' during collector business registration");
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'lokasi_koordinat' => 'required|array',
                'lokasi_koordinat.lat' => 'required|numeric|between:-90,90',
                'lokasi_koordinat.lng' => 'required|numeric|between:-180,180',
                'alamat' => 'required|string',
                'no_telepon' => 'required|string|max:20',
                'jenis_ikan_diterima' => 'required|array',
                'jenis_ikan_diterima.*' => 'string|max:255',
                'rate_harga_per_kg' => 'required|numeric|min:0',
                'kapasitas_maksimal' => 'required|numeric|min:1',
                'jam_operasional_mulai' => 'required|date_format:H:i',
                'jam_operasional_selesai' => 'required|date_format:H:i',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'deskripsi' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                \Log::error('Collector validation failed', [
                    'errors' => $validator->errors()->toArray(),
                    'request_data' => $request->except(['foto'])
                ]);
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
            \Log::info("Getting pending appointments for collector ID: $id");
            
            $collector = Collector::findOrFail($id);
            \Log::info("Found collector: " . $collector->nama_pengepul);
            
            // Check ownership
            if ($collector->user_id !== Auth::id()) {
                \Log::warning("Unauthorized access attempt by user " . Auth::id() . " for collector " . $collector->user_id);
                return $this->error('Unauthorized', 403);
            }

            \Log::info("Searching appointments with collector_id: " . $collector->id . " and status: menunggu");
            
            $appointments = Appointment::with(['fishFarm', 'user'])
                ->where('collector_id', $collector->id)
                ->where('status', 'menunggu') // Fix: use correct status value
                ->orderBy('tanggal_janji', 'asc') // Fix: use correct column name
                ->get();

            \Log::info("Found " . $appointments->count() . " pending appointments");
            
            return $this->success($appointments, 'Pending appointments retrieved successfully');
        } catch (\Exception $e) {
            \Log::error("Error in getPendingAppointments: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
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

            // Get current user coordinates - prioritize request params, then user profile
            $latitude = $request->get('lat') ?: $user->latitude;
            $longitude = $request->get('lng') ?: $user->longitude;
            
            if (!$latitude || !$longitude) {
                return $this->error('Location coordinates are required to find nearest collectors. Please update your location first.', 400);
            }

            $validator = Validator::make($request->all(), [
                'lat' => 'nullable|numeric|between:-90,90',
                'lng' => 'nullable|numeric|between:-180,180',
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

            $maxDistance = $request->get('max_distance', 100); // Increased default to 100km
            $perPage = $request->get('per_page', 20); // Increased default results

            \Log::info('Searching nearest collectors', [
                'user_id' => $user->id,
                'user_coordinates' => [$latitude, $longitude],
                'max_distance' => $maxDistance,
                'filters' => $request->only(['fish_type', 'min_rate', 'max_rate', 'min_capacity'])
            ]);

            // Get all active collectors with their user information
            $collectorsQuery = Collector::with(['user'])
                ->where('status', 'aktif');

            // Apply filters
            if ($request->has('fish_type') && $request->fish_type) {
                $collectorsQuery->whereJsonContains('jenis_ikan_diterima', $request->fish_type);
            }

            if ($request->has('min_rate') && $request->min_rate) {
                $collectorsQuery->where('rate_per_kg', '>=', $request->min_rate);
            }
            if ($request->has('max_rate') && $request->max_rate) {
                $collectorsQuery->where('rate_per_kg', '<=', $request->max_rate);
            }

            if ($request->has('min_capacity') && $request->min_capacity) {
                $collectorsQuery->where('kapasitas_maximum', '>=', $request->min_capacity);
            }

            $collectors = $collectorsQuery->get();

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
                    'user_location' => [
                        'lat' => (float) $latitude,
                        'lng' => (float) $longitude
                    ],
                    'search_radius' => $maxDistance . ' km'
                ], 'No collectors found matching your criteria');
            }

            // Calculate distances and collect nearby collectors
            $nearbyCollectors = collect();
            
            foreach ($collectors as $collector) {
                // Try to get coordinates from multiple sources
                $collectorLat = null;
                $collectorLng = null;
                
                // Priority 1: User profile coordinates
                if ($collector->user && $collector->user->latitude && $collector->user->longitude) {
                    $collectorLat = $collector->user->latitude;
                    $collectorLng = $collector->user->longitude;
                }
                
                // Priority 2: Collector lokasi_koordinat if available
                if (!$collectorLat && $collector->lokasi_koordinat) {
                    if (is_array($collector->lokasi_koordinat)) {
                        $collectorLat = $collector->lokasi_koordinat['lat'] ?? null;
                        $collectorLng = $collector->lokasi_koordinat['lng'] ?? null;
                    } elseif (is_string($collector->lokasi_koordinat)) {
                        $coords = json_decode($collector->lokasi_koordinat, true);
                        if ($coords && isset($coords['lat'], $coords['lng'])) {
                            $collectorLat = $coords['lat'];
                            $collectorLng = $coords['lng'];
                        }
                    }
                }
                
                if ($collectorLat && $collectorLng) {
                    $distance = $this->calculateDistance(
                        $latitude, 
                        $longitude, 
                        $collectorLat, 
                        $collectorLng
                    );
                    
                    if ($distance !== null && $distance <= $maxDistance) {
                        // Add distance information to collector
                        $collector->distance = round($distance, 3); // More precision for calculation
                        $collector->distance_formatted = $this->formatDistance($distance); // Use new format function
                        $collector->collector_coordinates = [
                            'lat' => (float) $collectorLat,
                            'lng' => (float) $collectorLng
                        ];
                        
                        $nearbyCollectors->push($collector);
                    }
                } else {
                    \Log::warning('Collector without coordinates', [
                        'collector_id' => $collector->id,
                        'collector_name' => $collector->nama_usaha,
                        'user_coordinates' => $collector->user ? [$collector->user->latitude, $collector->user->longitude] : null
                    ]);
                }
            }

            // Sort by distance (ascending - nearest first)
            $nearbyCollectors = $nearbyCollectors->sortBy('distance')->values();

            \Log::info('Found nearby collectors', [
                'total_collectors_checked' => $collectors->count(),
                'nearby_collectors_found' => $nearbyCollectors->count(),
                'within_radius' => $maxDistance . ' km'
            ]);

            // Manual pagination
            $currentPage = max(1, (int) $request->get('page', 1));
            $offset = ($currentPage - 1) * $perPage;
            $paginatedCollectors = $nearbyCollectors->slice($offset, $perPage)->values();

            $totalItems = $nearbyCollectors->count();
            $lastPage = $totalItems > 0 ? ceil($totalItems / $perPage) : 1;

            $pagination = [
                'current_page' => $currentPage,
                'per_page' => (int) $perPage,
                'total' => $totalItems,
                'last_page' => $lastPage,
                'from' => $totalItems > 0 ? $offset + 1 : 0,
                'to' => min($offset + $perPage, $totalItems)
            ];

            return $this->success([
                'data' => $paginatedCollectors,
                'pagination' => $pagination,
                'user_location' => [
                    'lat' => (float) $latitude,
                    'lng' => (float) $longitude
                ],
                'search_radius' => $maxDistance . ' km',
                'search_summary' => [
                    'total_collectors_checked' => $collectors->count(),
                    'collectors_within_radius' => $totalItems,
                    'current_page_showing' => $paginatedCollectors->count()
                ]
            ], $totalItems > 0 
                ? "Found {$totalItems} collectors within {$maxDistance}km radius" 
                : 'No collectors found within the specified radius'
            );

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

    /**
     * Get statistics for current user's collector
     */
    public function getCurrentUserStatistics(Request $request)
    {
        try {
            $user = $request->user();
            $collector = Collector::where('user_id', $user->id)->first();
            
            if (!$collector) {
                return $this->error('No collector found for current user', 404);
            }
            
            return $this->getStatistics($collector->id);
        } catch (\Exception $e) {
            return $this->error('Failed to get statistics', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get statistics for a specific collector
     */
    public function getStatistics($id)
    {
        try {
            $collector = Collector::findOrFail($id);
            
            // Get appointment statistics
            $totalAppointments = Appointment::where('collector_id', $collector->id)->count();
            $acceptedAppointments = Appointment::where('collector_id', $collector->id)
                ->where('status', 'diterima')->count();
            $completedAppointments = Appointment::where('collector_id', $collector->id)
                ->where('status', 'selesai')->count();
            
            // Calculate total weight and revenue
            $completedData = Appointment::where('collector_id', $collector->id)
                ->where('status', 'selesai')
                ->selectRaw('SUM(berat_aktual) as total_kg, SUM(total_final) as total_revenue')
                ->first();
            
            $totalKg = $completedData->total_kg ?? 0;
            $totalRevenue = $completedData->total_revenue ?? 0;
            
            // Get fish types statistics
            $fishTypes = Appointment::where('collector_id', $collector->id)
                ->where('status', 'selesai')
                ->whereNotNull('kualitas_ikan')
                ->selectRaw('kualitas_ikan as type, COUNT(*) as count')
                ->groupBy('kualitas_ikan')
                ->get();
            
            return $this->success([
                'total' => $totalAppointments,
                'accepted' => $acceptedAppointments,
                'completed' => $completedAppointments,
                'total_kg' => $totalKg,
                'revenue' => $totalRevenue,
                'fish_types' => $fishTypes
            ]);
            
        } catch (\Exception $e) {
            return $this->error('Failed to get collector statistics', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            \Log::warning('Missing coordinates for distance calculation', [
                'lat1' => $lat1, 'lon1' => $lon1, 'lat2' => $lat2, 'lon2' => $lon2
            ]);
            return null;
        }

        // Convert to float to ensure numeric calculation
        $lat1 = (float) $lat1;
        $lon1 = (float) $lon1;
        $lat2 = (float) $lat2;
        $lon2 = (float) $lon2;

        // Check if coordinates are valid
        if ($lat1 == 0 && $lon1 == 0 || $lat2 == 0 && $lon2 == 0) {
            \Log::warning('Invalid coordinates (0,0) detected', [
                'user_coords' => [$lat1, $lon1], 
                'collector_coords' => [$lat2, $lon2]
            ]);
            return null;
        }

        $earth_radius = 6371; // Earth's radius in kilometers

        $lat1_rad = deg2rad($lat1);
        $lon1_rad = deg2rad($lon1);
        $lat2_rad = deg2rad($lat2);
        $lon2_rad = deg2rad($lon2);

        $delta_lat = $lat2_rad - $lat1_rad;
        $delta_lon = $lon2_rad - $lon1_rad;

        $a = sin($delta_lat / 2) * sin($delta_lat / 2) +
             cos($lat1_rad) * cos($lat2_rad) *
             sin($delta_lon / 2) * sin($delta_lon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        $distance = $earth_radius * $c;
        
        \Log::info('Distance calculated', [
            'from' => [$lat1, $lon1],
            'to' => [$lat2, $lon2], 
            'distance_km' => $distance
        ]);
        
        return $distance;
    }

    /**
     * Format distance with appropriate unit (meters for close, km for far)
     */
    private function formatDistance($distanceKm)
    {
        if ($distanceKm === null) {
            return 'Jarak tidak diketahui';
        }

        if ($distanceKm < 1) {
            // Convert to meters for distances less than 1km
            $meters = round($distanceKm * 1000);
            return $meters . ' m';
        } else {
            // Use kilometers for distances 1km and above
            return number_format($distanceKm, 1) . ' km';
        }
    }
}

