<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Http\JsonResponse;
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Mendapatkan daftar produk.
     *
     * @param  \Illuminate\Http\Request  $request
<<<<<<< HEAD
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
=======
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    {
        $query = Product::with(['category', 'seller'])
                        ->where('aktif', true)
                        ->where('stok', '>', 0);

        // Filter berdasarkan kategori
        if ($request->has('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan jenis ikan
        if ($request->has('jenis_ikan')) {
            $query->where('jenis_ikan', $request->jenis_ikan);
        }

        // Pengurutan
        $sortField = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
<<<<<<< HEAD
        
        $allowedSortFields = ['nama', 'harga', 'created_at', 'rating_rata'];
        
=======

        $allowedSortFields = ['nama', 'harga', 'created_at', 'rating_rata'];

>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginasi
        $perPage = $request->per_page ?? 10;
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Mendapatkan daftar produk unggulan.
     *
<<<<<<< HEAD
     * @return \Illuminate\Http\JsonResponse
     */
    public function featured(): JsonResponse
=======
     * @return \Illuminate\Http\Response
     */
    public function featured()
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    {
        $products = Product::with(['category', 'seller'])
                          ->where('aktif', true)
                          ->where('unggulan', true)
                          ->where('stok', '>', 0)
                          ->orderBy('created_at', 'desc')
                          ->take(10)
                          ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Mendapatkan detail produk.
     *
     * @param  \App\Models\Product  $product
<<<<<<< HEAD
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product): JsonResponse
=======
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    {
        if (!$product->aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $product->load([
<<<<<<< HEAD
            'category', 
            'seller', 
            'seller.sellerLocations',
=======
            'category',
            'seller',
            'seller.sellerLocation',
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
            'reviews' => function ($query) {
                $query->with(['user', 'reviewReply', 'reviewReply.user'])
                      ->orderBy('created_at', 'desc')
                      ->take(5);
            }
        ]);

        // Dapatkan produk terkait (dari kategori yang sama)
        $relatedProducts = Product::where('kategori_id', $product->kategori_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('aktif', true)
                                 ->take(4)
                                 ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'related_products' => $relatedProducts
            ]
        ]);
    }

    /**
     * Mendapatkan produk berdasarkan kategori.
     *
     * @param  \App\Models\Category  $category
     * @param  \Illuminate\Http\Request  $request
<<<<<<< HEAD
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory(Category $category, Request $request): JsonResponse
=======
     * @return \Illuminate\Http\Response
     */
    public function byCategory(Category $category, Request $request)
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    {
        // Ambil semua ID kategori anak
        $categoryIds = [$category->id];
        $childCategories = $category->children()->pluck('id')->toArray();
        $categoryIds = array_merge($categoryIds, $childCategories);
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        $query = Product::with(['category', 'seller'])
                        ->whereIn('kategori_id', $categoryIds)
                        ->where('aktif', true)
                        ->where('stok', '>', 0);

        // Filter berdasarkan jenis ikan
        if ($request->has('jenis_ikan')) {
            $query->where('jenis_ikan', $request->jenis_ikan);
        }

        // Filter rentang harga
        if ($request->has('min_price')) {
            $query->where('harga', '>=', $request->min_price);
        }
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        if ($request->has('max_price')) {
            $query->where('harga', '<=', $request->max_price);
        }

        // Pengurutan
        $sortField = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
<<<<<<< HEAD
        
        $allowedSortFields = ['nama', 'harga', 'created_at', 'rating_rata'];
        
=======

        $allowedSortFields = ['nama', 'harga', 'created_at', 'rating_rata'];

>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginasi
        $perPage = $request->per_page ?? 10;
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'products' => $products
            ]
        ]);
    }

    /**
     * Mencari produk berdasarkan kata kunci.
     *
     * @param  string  $keyword
     * @param  \Illuminate\Http\Request  $request
<<<<<<< HEAD
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($keyword, Request $request): JsonResponse
=======
     * @return \Illuminate\Http\Response
     */
    public function search($keyword, Request $request)
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    {
        $query = Product::with(['category', 'seller'])
                        ->where(function($q) use ($keyword) {
                            $q->where('nama', 'like', "%{$keyword}%")
                              ->orWhere('deskripsi', 'like', "%{$keyword}%")
                              ->orWhere('spesies_ikan', 'like', "%{$keyword}%");
                        })
                        ->where('aktif', true)
                        ->where('stok', '>', 0);

        // Filter berdasarkan kategori
        if ($request->has('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan jenis ikan
        if ($request->has('jenis_ikan')) {
            $query->where('jenis_ikan', $request->jenis_ikan);
        }

        // Filter rentang harga
        if ($request->has('min_price')) {
            $query->where('harga', '>=', $request->min_price);
        }
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        if ($request->has('max_price')) {
            $query->where('harga', '<=', $request->max_price);
        }

        // Pengurutan
        $sortField = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
<<<<<<< HEAD
        
        $allowedSortFields = ['nama', 'harga', 'created_at', 'rating_rata'];
        
=======

        $allowedSortFields = ['nama', 'harga', 'created_at', 'rating_rata'];

>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginasi
        $perPage = $request->per_page ?? 10;
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'keyword' => $keyword,
                'products' => $products
            ]
        ]);
    }

    /**
     * Mendapatkan daftar produk penjual.
     *
     * @param  \Illuminate\Http\Request  $request
<<<<<<< HEAD
     * @return \Illuminate\Http\JsonResponse
     */
    public function sellerProducts(Request $request): JsonResponse
=======
     * @return \Illuminate\Http\Response
     */
    public function sellerProducts(Request $request)
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    {
        $user = $request->user();

        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $query = Product::with('category')
                        ->where('penjual_id', $user->id);

        // Filter berdasarkan status aktif
        if ($request->has('aktif')) {
            $query->where('aktif', $request->boolean('aktif'));
        }

        // Filter berdasarkan kategori
        if ($request->has('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan jenis ikan
        if ($request->has('jenis_ikan')) {
            $query->where('jenis_ikan', $request->jenis_ikan);
        }

        // Filter berdasarkan stok
        if ($request->has('stok_habis')) {
            if ($request->boolean('stok_habis')) {
                $query->where('stok', 0);
            } else {
                $query->where('stok', '>', 0);
            }
        }

        // Pengurutan
        $sortField = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
<<<<<<< HEAD
        
        $allowedSortFields = ['nama', 'harga', 'stok', 'created_at'];
        
=======

        $allowedSortFields = ['nama', 'harga', 'stok', 'created_at'];

>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginasi
        $perPage = $request->per_page ?? 10;
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Menyimpan produk baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
<<<<<<< HEAD
    public function store(Request $request): JsonResponse
=======
    public function store(Request $request)
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    {
        $user = $request->user();

        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
<<<<<<< HEAD
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0',
=======
            'nama' => 'required|string|min:3|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0.01',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0.01',
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
            'jenis_ikan' => 'required|in:segar,beku,olahan,hidup',
            'spesies_ikan' => 'nullable|string|max:255',
            'aktif' => 'boolean',
            'unggulan' => 'boolean',
<<<<<<< HEAD
            'gambar.*' => 'image|mimes:jpeg,png,jpg|max:2048',
=======
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama.required' => 'Nama produk harus diisi',
            'nama.min' => 'Nama produk minimal 3 karakter',
            'nama.max' => 'Nama produk maksimal 255 karakter',
            'kategori_id.required' => 'Kategori harus dipilih',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga harus lebih dari 0',
            'stok.required' => 'Stok harus diisi',
            'stok.integer' => 'Stok harus berupa angka bulat',
            'stok.min' => 'Stok tidak boleh negatif',
            'berat.required' => 'Berat harus diisi',
            'berat.numeric' => 'Berat harus berupa angka',
            'berat.min' => 'Berat harus lebih dari 0',
            'jenis_ikan.required' => 'Jenis ikan harus dipilih',
            'jenis_ikan.in' => 'Jenis ikan tidak valid',
            'gambar.*.image' => 'File harus berupa gambar',
            'gambar.*.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'gambar.*.max' => 'Ukuran gambar maksimal 2MB',
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

<<<<<<< HEAD
=======
        // Validasi jumlah gambar (maksimal 5)
        if ($request->hasFile('gambar') && count($request->file('gambar')) > 5) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal 5 gambar dapat diunggah'
            ], 422);
        }

>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        // Upload gambar
        $imageNames = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $image) {
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/products', $imageName);
                $imageNames[] = 'products/' . $imageName;
            }
        }

        $product = Product::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama) . '-' . Str::random(5),
            'kategori_id' => $request->kategori_id,
            'penjual_id' => $user->id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'berat' => $request->berat,
            'jenis_ikan' => $request->jenis_ikan,
            'spesies_ikan' => $request->spesies_ikan,
            'aktif' => $request->has('aktif') ? $request->aktif : true,
            'unggulan' => $request->has('unggulan') ? $request->unggulan : false,
            'gambar' => $imageNames,
        ]);

        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product
        ], 201);
    }

    /**
     * Memperbarui produk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
<<<<<<< HEAD
    public function update(Request $request, Product $product): JsonResponse
=======
    public function update(Request $request, Product $product)
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    {
        $user = $request->user();

        if (!$user->hasRole('seller') || $product->penjual_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:255',
            'kategori_id' => 'sometimes|exists:categories,id',
            'deskripsi' => 'sometimes|string',
            'harga' => 'sometimes|numeric|min:0',
            'stok' => 'sometimes|integer|min:0',
            'berat' => 'sometimes|numeric|min:0',
            'jenis_ikan' => 'sometimes|in:segar,beku,olahan,hidup',
            'spesies_ikan' => 'nullable|string|max:255',
            'aktif' => 'sometimes|boolean',
            'unggulan' => 'sometimes|boolean',
            'gambar_baru.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'hapus_gambar' => 'nullable|array',
            'hapus_gambar.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Perbarui gambar jika ada
        $imageNames = $product->gambar ?? [];

        // Hapus gambar yang ditandai untuk dihapus
        if ($request->has('hapus_gambar') && is_array($request->hapus_gambar)) {
            foreach ($request->hapus_gambar as $imageToDelete) {
                $key = array_search($imageToDelete, $imageNames);
                if ($key !== false) {
                    Storage::delete('public/' . $imageToDelete);
                    unset($imageNames[$key]);
                }
            }
            $imageNames = array_values($imageNames); // Reindex array
        }

        // Tambahkan gambar baru
        if ($request->hasFile('gambar_baru')) {
            foreach ($request->file('gambar_baru') as $image) {
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/products', $imageName);
                $imageNames[] = 'products/' . $imageName;
            }
        }

        // Update data produk
        $productData = $request->only([
<<<<<<< HEAD
            'nama', 'kategori_id', 'deskripsi', 'harga', 
            'stok', 'berat', 'jenis_ikan', 'spesies_ikan', 
=======
            'nama', 'kategori_id', 'deskripsi', 'harga',
            'stok', 'berat', 'jenis_ikan', 'spesies_ikan',
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
            'aktif', 'unggulan'
        ]);

        // Update slug jika nama diubah
        if ($request->has('nama')) {
            $productData['slug'] = Str::slug($request->nama) . '-' . Str::random(5);
        }

        // Update gambar
        $productData['gambar'] = $imageNames;

        $product->update($productData);
        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data' => $product
        ]);
    }

    /**
     * Menghapus produk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
<<<<<<< HEAD
    public function destroy(Request $request, Product $product): JsonResponse
=======
    public function destroy(Request $request, Product $product)
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    {
        $user = $request->user();

        if (!$user->hasRole('seller') || $product->penjual_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Hapus gambar produk
        if ($product->gambar) {
            foreach ($product->gambar as $image) {
                Storage::delete('public/' . $image);
            }
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
