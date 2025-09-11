<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * Create a new appointment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
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
            $user = Auth::user();
            
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
     * Get appointments for the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAppointments(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $appointments = Appointment::where('pembeli_id', $user->id)
                ->with(['seller:id,name,email', 'sellerLocation:id,nama_usaha,alamat_lengkap'])
                ->orderBy('tanggal_janji', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get appointments for a seller
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSellerAppointments(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || !$user->hasRole('seller')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $appointments = Appointment::where('penjual_id', $user->id)
                ->with(['buyer:id,name,email', 'sellerLocation:id,nama_usaha,alamat_lengkap'])
                ->orderBy('tanggal_janji', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update appointment status
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,dikonfirmasi,selesai,dibatalkan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $appointment = Appointment::findOrFail($id);

            // Check if the user is authorized to update this appointment
            if ($user->id != $appointment->penjual_id && $user->id != $appointment->pembeli_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this appointment'
                ], 403);
            }

            // Only sellers can confirm appointments
            if ($request->status == 'dikonfirmasi' && $user->id != $appointment->penjual_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only sellers can confirm appointments'
                ], 403);
            }

            // Update the appointment status
            $appointment->status = $request->status;
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Appointment status updated successfully',
                'data' => $appointment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating appointment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
