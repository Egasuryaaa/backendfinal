<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\SellerLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentViewController extends Controller
{
    /**
     * Show the form to create a new appointment
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk membuat janji temu.');
        }
        
        // Get location details from query parameters
        $locationId = $request->query('location_id');
        $sellerId = $request->query('seller_id');
        
        if (!$locationId || !$sellerId) {
            return redirect()->route('locations.index')->with('error', 'Informasi lokasi tidak lengkap.');
        }
        
        // Get location and seller details
        $sellerLocation = SellerLocation::findOrFail($locationId);
        $seller = User::findOrFail($sellerId);
        
        return view('appointments.create', [
            'sellerLocation' => $sellerLocation,
            'seller' => $seller
        ]);
    }
    
    /**
     * Store a new appointment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk membuat janji temu.');
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'seller_id' => 'required|exists:users,id',
            'location_id' => 'required|exists:seller_locations,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'meeting_latitude' => 'nullable|numeric|between:-90,90',
            'meeting_longitude' => 'nullable|numeric|between:-180,180',
            'meeting_address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Create appointment date time
            $appointmentDateTime = $request->date . ' ' . $request->time;
            
            // Prepare meeting location data
            $meetingLocation = null;
            if ($request->meeting_latitude && $request->meeting_longitude) {
                $meetingLocation = [
                    'lat' => (float) $request->meeting_latitude,
                    'lng' => (float) $request->meeting_longitude,
                    'address' => $request->meeting_address ?? ''
                ];
            }
            
            // Create the appointment
            $appointment = Appointment::create([
                'pembeli_id' => Auth::id(),
                'penjual_id' => $request->seller_id,
                'lokasi_penjual_id' => $request->location_id,
                'tanggal_janji' => $appointmentDateTime,
                'tujuan' => $request->purpose,
                'catatan' => $request->notes,
                'meeting_location' => $meetingLocation,
                'status' => 'menunggu',
            ]);

            return redirect()->route('appointments.history')
                ->with('success', 'Janji temu berhasil dibuat. Anda akan dihubungi oleh penjual segera.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat janji temu: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Show user appointment history
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function history(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melihat riwayat janji temu.');
        }
        
        $user = Auth::user();
        $status = $request->get('status');
        
        $query = Appointment::where(function($q) use ($user) {
            $q->where('pembeli_id', $user->id)
              ->orWhere('penjual_id', $user->id);
        });
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $appointments = $query->with(['seller', 'buyer', 'sellerLocation'])
            ->orderBy('tanggal_janji', 'desc')
            ->paginate(10);
            
        return view('appointments.history', [
            'appointments' => $appointments,
            'status' => $status
        ]);
    }
    
    /**
     * Show appointment details
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, $id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melihat detail janji temu.');
        }
        
        $user = Auth::user();
        $appointment = Appointment::with(['seller', 'buyer', 'sellerLocation'])
            ->where(function($q) use ($user) {
                $q->where('pembeli_id', $user->id)
                  ->orWhere('penjual_id', $user->id);
            })
            ->findOrFail($id);
        
        return view('appointments.show', [
            'appointment' => $appointment
        ]);
    }
    
    /**
     * Update appointment status
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,dikonfirmasi,selesai,dibatalkan',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();
        $appointment = Appointment::findOrFail($id);

        // Check if the user is authorized to update this appointment
        if ($user->id != $appointment->penjual_id && $user->id != $appointment->pembeli_id) {
            return back()->with('error', 'Anda tidak diizinkan mengubah janji temu ini.');
        }

        // Only sellers can confirm appointments
        if ($request->status == 'dikonfirmasi' && $user->id != $appointment->penjual_id) {
            return back()->with('error', 'Hanya penjual yang dapat mengkonfirmasi janji temu.');
        }

        // Update the appointment status
        $appointment->status = $request->status;
        $appointment->save();

        return back()->with('success', 'Status janji temu berhasil diperbarui.');
    }
}
