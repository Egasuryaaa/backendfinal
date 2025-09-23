<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Cart;

class AuthController extends Controller
{
    /**
     * Registrasi pengguna baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|in:pembeli,penjual_biasa,pengepul,pemilik_tambak',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role ?? 'pembeli', // default role is pembeli
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Buat keranjang untuk pengguna baru
        Cart::create([
            'user_id' => $user->id
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => $user->role,
                'role_display' => $user->getRoleDisplayName()
            ]
        ], 201);
    }

    /**
     * Login pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Hapus token yang ada sebelumnya (opsional)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => $user->role,
                'role_display' => $user->getRoleDisplayName(),
                'has_coordinates' => $user->hasCoordinates(),
                'coordinates' => $user->getCoordinates()
            ]
        ]);
    }

    /**
     * Save token to session for web authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSessionToken(Request $request)
    {
        $token = $request->input('token');

        if ($token) {
            // Verify token is valid
            $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

            if ($accessToken && $accessToken->tokenable) {
                // Save token to session
                $request->session()->put('auth_token', $token);

                return response()->json([
                    'success' => true,
                    'message' => 'Token saved to session'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid token'
        ], 400);
    }

    /**
     * Logout pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Mendapatkan detail pengguna yang sedang login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'role' => $user->role,
                'is_seller' => $user->isPenjualBiasa(),
                'is_admin' => $user->isAdmin(),
                'is_pengepul' => $user->isPengepul(),
                'is_pemilik_tambak' => $user->isPemilikTambak(),
                'is_pembeli' => $user->isPembeli(),
            ]
        ]);
    }

    /**
     * Memperbarui detail pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            //'phone' => 'nullable|string|max:20',
            'current_password' => 'sometimes|required_with:password|string',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verifikasi password saat ini jika pengguna ingin mengubah password
        if ($request->has('current_password') && $request->has('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini tidak valid'
                ], 422);
            }
        }

        // Update detail pengguna
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Register user sebagai seller/penjual_biasa
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerAsSeller(Request $request)
    {
        try {
            $user = $request->user();

            // Log untuk debugging
            Log::info('Register as seller attempt', [
                'user_id' => $user ? $user->id : 'null',
                'current_role' => $user ? $user->role : 'null'
            ]);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            // Check if user is already a seller
            if ($user->role === 'penjual_biasa' || $user->role === 'seller') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah terdaftar sebagai penjual'
                ], 400);
            }

            // Update user role to seller
            $user->update([
                'role' => 'penjual_biasa'
            ]);

            Log::info('User role updated successfully', [
                'user_id' => $user->id,
                'new_role' => $user->fresh()->role
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendaftar sebagai penjual',
                'data' => [
                    'user' => $user->fresh()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in registerAsSeller', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update store information for seller
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStoreInfo(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'nama_toko' => 'required|string|max:255',
                'telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
                'kota' => 'required|string|max:100',
                'provinsi' => 'required|string|max:100',
                'deskripsi' => 'nullable|string',
                'jam_buka' => 'nullable|string|max:10',
                'jam_tutup' => 'nullable|string|max:10',
                'active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update user information with store data
            $user->update([
                'nama_toko' => $request->nama_toko,
                'phone' => $request->telepon,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'provinsi' => $request->provinsi,
                'deskripsi' => $request->deskripsi,
                'jam_buka' => $request->jam_buka,
                'jam_tutup' => $request->jam_tutup,
                'active' => $request->active ?? true
            ]);

            Log::info('Store info updated successfully', [
                'user_id' => $user->id,
                'store_name' => $request->nama_toko
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Informasi toko berhasil diperbarui',
                'data' => [
                    'user' => $user->fresh()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in updateStoreInfo', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile with store information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Profil berhasil diambil'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in profile', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
}

