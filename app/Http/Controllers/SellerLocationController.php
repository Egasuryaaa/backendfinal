<?php

namespace App\Http\Controllers;

use App\Models\SellerLocation;
use Illuminate\Http\Request;

class SellerLocationController extends Controller
{
    /**
     * Display a listing of active seller locations.
     */
    public function index()
    {
        $locations = SellerLocation::with(['user'])
            ->where('aktif', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Format data untuk frontend
        $formattedLocations = $locations->map(function ($location) {
            return [
                'id' => $location->id,
                'nama_usaha' => $location->nama_usaha,
                'deskripsi' => $location->deskripsi,
                'alamat_lengkap' => $location->alamat_lengkap,
                'telepon' => $location->telepon,
                'jenis_penjual' => $location->jenis_penjual,
                'jenis_penjual_text' => $location->seller_type_text,
                'latitude' => (float) $location->latitude,
                'longitude' => (float) $location->longitude,
                'penjual_nama' => $location->user ? $location->user->name : 'Tidak diketahui',
                'jam_operasional' => $location->formatted_operating_hours,
                'foto_utama' => $location->main_photo_url,
                'alamat_singkat' => $location->short_address,
                'alamat_lengkap_formatted' => $location->full_address,
            ];
        });

        return view('locations.index', [
            'locations' => $formattedLocations,
            'totalLocations' => $locations->count(),
            'defaultCenter' => [
                'lat' => -7.1192,
                'lng' => 112.4186
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat_lengkap' => 'required|string',
            'telepon' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'jenis_penjual' => 'required|in:' . implode(',', array_keys(SellerLocation::$sellerTypes)),
        ]);

        $validated['user_id'] = auth()->id();
        $validated['aktif'] = true;

        $location = SellerLocation::create($validated);

        return redirect()->route('locations')->with('success', 'Lokasi penjual berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SellerLocation $location)
    {
        $location->load('user');
        
        return view('locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SellerLocation $location)
    {
        // Check if user can edit this location
        if (auth()->user()->id !== $location->user_id && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SellerLocation $location)
    {
        // Check if user can update this location
        if (auth()->user()->id !== $location->user_id && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat_lengkap' => 'required|string',
            'telepon' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'jenis_penjual' => 'required|in:' . implode(',', array_keys(SellerLocation::$sellerTypes)),
        ]);

        $location->update($validated);

        return redirect()->route('locations')->with('success', 'Lokasi penjual berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SellerLocation $location)
    {
        // Check if user can delete this location
        if (auth()->user()->id !== $location->user_id && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $location->delete();

        return redirect()->route('locations')->with('success', 'Lokasi penjual berhasil dihapus!');
    }

    /**
     * API endpoint to get locations data as JSON
     */
    public function getLocationsJson()
    {
        $locations = SellerLocation::with(['user'])
            ->where('aktif', true)
            ->get();

        return response()->json($locations->map(function ($location) {
            return [
                'id' => $location->id,
                'nama_usaha' => $location->nama_usaha,
                'deskripsi' => $location->deskripsi,
                'alamat_lengkap' => $location->alamat_lengkap,
                'telepon' => $location->telepon,
                'jenis_penjual' => $location->jenis_penjual,
                'jenis_penjual_text' => $location->seller_type_text,
                'latitude' => (float) $location->latitude,
                'longitude' => (float) $location->longitude,
                'penjual_nama' => $location->user ? $location->user->name : 'Tidak diketahui',
                'jam_operasional' => $location->formatted_operating_hours,
                'foto_utama' => $location->main_photo_url,
            ];
        }));
    }
}
