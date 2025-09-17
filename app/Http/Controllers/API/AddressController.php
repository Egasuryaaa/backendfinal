<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Address;

class AddressController extends Controller
{
    /**zz
     * Mendapatkan semua alamat untuk pengguna yang login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $addresses = $user->addresses()->orderBy('utama', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $addresses
        ]);
    }

    /**
     * Menyimpan alamat baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'label' => 'nullable|string|max:255',
            'nama_penerima' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'provinsi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'utama' => 'boolean',
            'catatan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        $addressData = $request->all();
        $addressData['user_id'] = $user->id;
        
        // Jika alamat ini diset sebagai utama, atur semua alamat lain menjadi bukan utama
        if ($request->has('utama') && $request->utama) {
            Address::where('user_id', $user->id)->update(['utama' => false]);
        }
        
        $address = Address::create($addressData);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan',
            'data' => $address
        ], 201);
    }

    /**
     * Menampilkan alamat tertentu.
     *
     * @param  \App\Models\Address  $address
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Address $address, Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Pastikan alamat milik pengguna
        if ($address->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }

    /**
     * Memperbarui alamat tertentu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Address $address): JsonResponse
    {
        $user = $request->user();
        
        // Pastikan alamat milik pengguna
        if ($address->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'label' => 'nullable|string|max:255',
            'nama_penerima' => 'sometimes|string|max:255',
            'telepon' => 'sometimes|string|max:20',
            'alamat_lengkap' => 'sometimes|string',
            'provinsi' => 'sometimes|string|max:255',
            'kota' => 'sometimes|string|max:255',
            'kecamatan' => 'sometimes|string|max:255',
            'kode_pos' => 'sometimes|string|max:10',
            'utama' => 'boolean',
            'catatan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Jika alamat ini diset sebagai utama, atur semua alamat lain menjadi bukan utama
        if ($request->has('utama') && $request->utama) {
            Address::where('user_id', $user->id)
                  ->where('id', '!=', $address->id)
                  ->update(['utama' => false]);
        }
        
        $address->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil diperbarui',
            'data' => $address
        ]);
    }

    /**
     * Menghapus alamat tertentu.
     *
     * @param  \App\Models\Address  $address
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Address $address, Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Pastikan alamat milik pengguna
        if ($address->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        // Cek apakah alamat digunakan dalam pesanan aktif
        $activeOrders = $address->orders()
                               ->whereNotIn('status', ['selesai', 'dibatalkan'])
                               ->exists();
                               
        if ($activeOrders) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat sedang digunakan dalam pesanan aktif'
            ], 400);
        }

        $address->delete();

        // Jika alamat yang dihapus adalah alamat utama, set alamat pertama sebagai utama (jika ada)
        if ($address->utama) {
            $newMainAddress = Address::where('user_id', $user->id)->first();
            if ($newMainAddress) {
                $newMainAddress->utama = true;
                $newMainAddress->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil dihapus'
        ]);
    }

    /**
     * Mengatur alamat sebagai alamat utama.
     *
     * @param  \App\Models\Address  $address
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAsMain(Address $address, Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Pastikan alamat milik pengguna
        if ($address->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Atur semua alamat lain menjadi bukan utama
        Address::where('user_id', $user->id)
              ->where('id', '!=', $address->id)
              ->update(['utama' => false]);
        
        // Atur alamat ini sebagai utama
        $address->utama = true;
        $address->save();

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil diatur sebagai alamat utama',
            'data' => $address
        ]);
    }
}

