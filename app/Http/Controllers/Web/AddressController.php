<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $addresses = Auth::user()->addresses()->orderBy('utama', 'desc')->get();
        
        // If this is an API request, return JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $addresses
            ]);
        }
        
        return view('addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('addresses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'kecamatan' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $isMain = !$user->addresses()->exists(); // Make first address the main one

        $address = $user->addresses()->create($request->all() + ['utama' => $isMain]);

        // If this is an API request, return JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil ditambahkan',
                'data' => $address
            ], 201);
        }

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        // Authorization check
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        return view('addresses.edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        // Authorization check
        if ($address->user_id !== Auth::id()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alamat tidak ditemukan'
                ], 404);
            }
            abort(403);
        }

        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'kecamatan' => 'nullable|string|max:100',
        ]);

        $address->update($request->all());

        // If this is an API request, return JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil diperbarui',
                'data' => $address->fresh()
            ]);
        }

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Address $address)
    {
        // Authorization check
        if ($address->user_id !== Auth::id()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alamat tidak ditemukan'
                ], 404);
            }
            abort(403);
        }

        // Logic to handle if the main address is deleted
        if ($address->utama && Auth::user()->addresses()->count() > 1) {
            $newMain = Auth::user()->addresses()->where('id', '!=', $address->id)->first();
            if ($newMain) {
                $newMain->setAsMain();
            }
        }

        $address->delete();

        // If this is an API request, return JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil dihapus'
            ]);
        }

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Set an address as the main address.
     */
    public function setMain(Request $request, Address $address)
    {
        // Authorization check
        if ($address->user_id !== Auth::id()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alamat tidak ditemukan'
                ], 404);
            }
            abort(403);
        }

        $address->setAsMain();

        // If this is an API request, return JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat utama berhasil diubah',
                'data' => $address->fresh()
            ]);
        }

        return redirect()->route('addresses.index')->with('success', 'Alamat utama berhasil diubah.');
    }

    /**
     * Set address as main address (API method alias)
     */
    public function setAsMain(Request $request, Address $address)
    {
        return $this->setMain($request, $address);
    }
}
