<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FishFarm;
use App\Models\Collector;
use App\Models\Appointment;
use App\Services\WhatsAppService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FishFarmController extends Controller
{
    use ApiResponse;

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display a listing of fish farms
     */
    public function index(Request $request)
    {
        try {
            $query = FishFarm::with('user');

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by fish type
            if ($request->has('jenis_ikan')) {
                $query->where('jenis_ikan', 'like', '%' . $request->jenis_ikan . '%');
            }

            // Filter by user (for own farms)
            if ($request->has('user_only') && $request->user_only == 'true') {
                $query->where('user_id', Auth::id());
            }

            $fishFarms = $query->paginate($request->get('per_page', 10));

            return $this->success($fishFarms, 'Fish farms retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve fish farms', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created fish farm
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Only allow pemilik_tambak to create fish farms
            if (!$user->isPemilikTambak() && !$user->isAdmin()) {
                return $this->error('Unauthorized - Only fish farm owners can create fish farms', 403);
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'banyak_bibit' => 'required|integer|min:1',
                'lokasi_koordinat' => 'required|array',
                'lokasi_koordinat.lat' => 'required|numeric|between:-90,90',
                'lokasi_koordinat.lng' => 'required|numeric|between:-180,180',
                'alamat' => 'required|string',
                'jenis_ikan' => 'required|string|max:255',
                'luas_tambak' => 'required|numeric|min:0.01',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'no_telepon' => 'required|string|max:20',
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
                $data['foto'] = $request->file('foto')->store('fish-farms', 'public');
            }

            $fishFarm = FishFarm::create($data);
            $fishFarm->load('user');

            return $this->success($fishFarm, 'Fish farm created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create fish farm', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified fish farm
     */
    public function show($id)
    {
        try {
            $fishFarm = FishFarm::with('user', 'appointments.collector')->findOrFail($id);
            
            // Add estimated production
            $fishFarm->estimated_production = $fishFarm->estimated_production;
            
            return $this->success($fishFarm, 'Fish farm retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Fish farm not found', 404);
        }
    }

    /**
     * Update the specified fish farm
     */
    public function update(Request $request, $id)
    {
        try {
            $fishFarm = FishFarm::findOrFail($id);

            // Check ownership
            if ($fishFarm->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'sometimes|string|max:255',
                'banyak_bibit' => 'sometimes|integer|min:1',
                'lokasi_koordinat' => 'sometimes|array',
                'lokasi_koordinat.lat' => 'required_with:lokasi_koordinat|numeric|between:-90,90',
                'lokasi_koordinat.lng' => 'required_with:lokasi_koordinat|numeric|between:-180,180',
                'alamat' => 'sometimes|string',
                'jenis_ikan' => 'sometimes|string|max:255',
                'luas_tambak' => 'sometimes|numeric|min:0.01',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'no_telepon' => 'sometimes|string|max:20',
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
                if ($fishFarm->foto) {
                    Storage::disk('public')->delete($fishFarm->foto);
                }
                $data['foto'] = $request->file('foto')->store('fish-farms', 'public');
            }

            $fishFarm->update($data);
            $fishFarm->load('user');

            return $this->success($fishFarm, 'Fish farm updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update fish farm', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified fish farm
     */
    public function destroy($id)
    {
        try {
            $fishFarm = FishFarm::findOrFail($id);

            // Check ownership
            if ($fishFarm->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            // Delete photo if exists
            if ($fishFarm->foto) {
                Storage::disk('public')->delete($fishFarm->foto);
            }

            $fishFarm->delete();

            return $this->success(null, 'Fish farm deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete fish farm', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get available collectors for this fish farm
     */
    public function getAvailableCollectors($id, Request $request)
    {
        try {
            $fishFarm = FishFarm::findOrFail($id);
            
            // Check ownership
            if ($fishFarm->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            $maxDistance = $request->get('max_distance', 50);
            $collectors = $fishFarm->getAvailableCollectors($maxDistance);

            // Add earnings calculation for each collector
            $collectorsWithEarnings = $collectors->map(function ($collector) use ($fishFarm) {
                $collector->potential_earnings = $collector->calculateEarnings($fishFarm);
                $collector->accepts_fish_type = $collector->acceptsFishType($fishFarm->jenis_ikan);
                return $collector;
            });

            return $this->success($collectorsWithEarnings, 'Available collectors retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve collectors', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Create appointment with collector
     */
    public function createAppointment(Request $request, $id)
    {
        try {
            $fishFarm = FishFarm::findOrFail($id);
            
            // Check ownership
            if ($fishFarm->user_id !== Auth::id()) {
                return $this->error('Unauthorized', 403);
            }

            $validator = Validator::make($request->all(), [
                'collector_id' => 'required|exists:collectors,id',
                'tanggal_penjemputan' => 'required|date|after:today',
                'perkiraan_berat' => 'required|numeric|min:1',
                'harga_per_kg' => 'required|numeric|min:0',
                'catatan' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->error('Validation failed', 422, $validator->errors());
            }

            $collector = Collector::findOrFail($request->collector_id);

            // Check if collector accepts this fish type
            if (!$collector->acceptsFishType($fishFarm->jenis_ikan)) {
                return $this->error('Collector does not accept this fish type', 400);
            }

            $appointment = Appointment::create([
                'user_id' => Auth::id(),
                'fish_farm_id' => $fishFarm->id,
                'collector_id' => $collector->id,
                'jenis' => 'fish_farm_pickup',
                'tanggal' => $request->tanggal_penjemputan,
                'status' => 'pending',
                'catatan' => $request->catatan,
                'perkiraan_berat' => $request->perkiraan_berat,
                'harga_per_kg' => $request->harga_per_kg,
                'total_estimasi' => $request->perkiraan_berat * $request->harga_per_kg,
                'whatsapp_sent' => false
            ]);

            $appointment->load(['fishFarm', 'collector']);

            // Send WhatsApp notification
            $this->whatsappService->sendFishFarmAppointmentSummary($appointment);

            return $this->success($appointment, 'Appointment created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create appointment', 500, ['error' => $e->getMessage()]);
        }
    }
}

