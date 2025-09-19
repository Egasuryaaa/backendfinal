<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SellerInfoController extends Controller
{
    /**
     * Get seller information by seller ID
     *
     * @param  int  $sellerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($sellerId): JsonResponse
    {
        $seller = User::find($sellerId);

        if (!$seller || !$seller->isSeller()) {
            return response()->json([
                'success' => false,
                'message' => 'Penjual tidak ditemukan'
            ], 404);
        }

        // Get seller's basic info
        $sellerInfo = [
            'id' => $seller->id,
            'name' => $seller->name,
            'store_name' => $seller->nama_toko ?? $seller->name,
            'description' => $seller->deskripsi ?? 'Belum ada deskripsi toko',
            'phone' => $seller->phone,
            'store_address' => [
                'full_address' => $seller->alamat,
                'city' => $seller->kota,
                'province' => $seller->provinsi,
                'note' => 'Alamat fisik toko yang dapat dikunjungi pembeli'
            ],
            'opening_hours' => [
                'open' => $seller->jam_buka,
                'close' => $seller->jam_tutup
            ],
            'is_active' => $seller->active,
            'joined_date' => $seller->created_at->format('d M Y'),
            'coordinates' => [
                'latitude' => $seller->latitude,
                'longitude' => $seller->longitude
            ]
        ];

        // Get seller's products count and rating
        $productsCount = Product::where('penjual_id', $seller->id)
                                ->where('aktif', true)
                                ->count();

        $avgRating = Product::where('penjual_id', $seller->id)
                           ->where('aktif', true)
                           ->avg('rating_rata') ?? 0;

        // Get seller locations if any
        $locations = $seller->sellerLocations()
                           ->where('aktif', true)
                           ->get()
                           ->map(function ($location) {
                               return [
                                   'id' => $location->id,
                                   'name' => $location->nama_usaha,
                                   'description' => $location->deskripsi,
                                   'store_address' => [
                                       'full_address' => $location->alamat_lengkap,
                                       'city' => $location->kota,
                                       'province' => $location->provinsi,
                                       'postal_code' => $location->kode_pos,
                                       'note' => 'Alamat cabang toko/lokasi usaha tambahan'
                                   ],
                                   'phone' => $location->telepon,
                                   'type' => $location->jenis_penjual,
                                   'coordinates' => [
                                       'latitude' => $location->latitude,
                                       'longitude' => $location->longitude
                                   ],
                                   'opening_hours' => $location->jam_operasional
                               ];
                           });

        return response()->json([
            'success' => true,
            'message' => 'Informasi toko berhasil diambil',
            'data' => [
                'seller' => $sellerInfo,
                'stats' => [
                    'products_count' => $productsCount,
                    'average_rating' => round($avgRating, 1)
                ],
                'locations' => $locations
            ],
            'meta' => [
                'type' => 'store_info',
                'description' => 'Informasi toko dan alamat fisik untuk dikunjungi pembeli'
            ]
        ]);
    }

    /**
     * Get seller bank account information (only for authenticated users with valid orders)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $sellerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBankAccount(Request $request, $sellerId): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $seller = User::find($sellerId);

        if (!$seller || !$seller->isSeller()) {
            return response()->json([
                'success' => false,
                'message' => 'Penjual tidak ditemukan'
            ], 404);
        }

        // Check if user has any pending manual payment orders with this seller
        $hasValidOrder = $user->orders()
            ->where('status', 'menunggu')
            ->where('metode_pembayaran', 'manual')
            ->whereHas('orderItems.product', function ($query) use ($sellerId) {
                $query->where('penjual_id', $sellerId);
            })
            ->exists();

        if (!$hasValidOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Informasi rekening hanya tersedia untuk pesanan dengan pembayaran manual yang aktif.'
            ], 403);
        }

        // Check if seller has bank account information
        if (!$seller->bank_name || !$seller->account_number || !$seller->account_holder_name) {
            return response()->json([
                'success' => false,
                'message' => 'Informasi rekening belum tersedia. Silakan hubungi penjual.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Informasi rekening berhasil diambil',
            'data' => [
                'bank_account' => [
                    'bank_name' => $seller->bank_name,
                    'account_number' => $seller->account_number,
                    'account_holder_name' => $seller->account_holder_name,
                    'seller_name' => $seller->name,
                ],
                'instructions' => 'Silakan transfer sesuai total pesanan ke rekening berikut. Upload bukti transfer setelah pembayaran.'
            ],
            'meta' => [
                'type' => 'bank_account',
                'description' => 'Informasi rekening untuk pembayaran manual'
            ]
        ]);
    }

    /**
     * Get seller info from product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFromProduct(Request $request): JsonResponse
    {
        $productId = $request->input('product_id');

        if (!$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID diperlukan'
            ], 400);
        }

        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return $this->show($product->penjual_id);
    }
}
