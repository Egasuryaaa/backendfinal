<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Appointment;
use App\Models\Review;
use Carbon\Carbon;

class SellerController extends Controller
{
    /**z
     * Mendapatkan data dashboard untuk penjual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        try {
            Log::info('Seller dashboard request started');

            $user = $request->user();
            Log::info('User retrieved: ' . ($user ? $user->id : 'null'));

            if (!$user->isSeller()) {
                Log::warning('User is not a seller: ' . $user->role);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. User role: ' . $user->role
                ], 403);
            }

            Log::info('User is seller, proceeding with queries');

            // Hitung total pendapatan
            $totalRevenue = OrderItem::where('penjual_id', $user->id)
                                   ->whereHas('order', function($q) {
                                       $q->where('status_pembayaran', 'dibayar');
                                   })
                                   ->sum('subtotal');
            Log::info('Total revenue calculated: ' . $totalRevenue);

            // Hitung jumlah pesanan
            $orderCount = OrderItem::where('penjual_id', $user->id)
                                 ->distinct('pesanan_id')
                                 ->count('pesanan_id');
            Log::info('Order count calculated: ' . $orderCount);

            // Hitung jumlah produk
            $productCount = Product::where('penjual_id', $user->id)->count();
            Log::info('Product count calculated: ' . $productCount);

            // Hitung jumlah produk stok habis
            $outOfStockCount = Product::where('penjual_id', $user->id)
                                    ->where('stok', 0)
                                    ->count();
            Log::info('Out of stock count calculated: ' . $outOfStockCount);

            // Hitung rating rata-rata
            $avgRating = Review::whereHas('product', function($q) use ($user) {
                               $q->where('penjual_id', $user->id);
                           })
                           ->avg('rating');

            $avgRating = round($avgRating ?: 0, 1);
            Log::info('Average rating calculated: ' . $avgRating);

            // Hitung jumlah ulasan yang belum dibalas
            $unrepliedReviews = Review::whereHas('product', function($q) use ($user) {
                                     $q->where('penjual_id', $user->id);
                                 })
                                 ->doesntHave('reviewReply')
                                 ->count();
            Log::info('Unreplied reviews calculated: ' . $unrepliedReviews);

        // Hitung jumlah pesanan berdasarkan status
        $orderStats = OrderItem::where('penjual_id', $user->id)
                             ->join('orders', 'order_items.pesanan_id', '=', 'orders.id')
                             ->select('orders.status', DB::raw('count(*) as count'))
                             ->groupBy('orders.status')
                             ->get()
                             ->pluck('count', 'status')
                             ->toArray();

        // Lengkapi dengan status yang kosong
        foreach (['menunggu', 'dibayar', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $status) {
            if (!isset($orderStats[$status])) {
                $orderStats[$status] = 0;
            }
        }

        // Dapatkan data penjualan 7 hari terakhir
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailySales = OrderItem::where('penjual_id', $user->id)
                                 ->whereHas('order', function($q) use ($date) {
                                     $q->where('status_pembayaran', 'dibayar')
                                       ->whereDate('created_at', $date);
                                 })
                                 ->sum('subtotal');

            $salesData[] = [
                'date' => Carbon::now()->subDays($i)->format('d M'),
                'sales' => $dailySales
            ];
        }

        // Dapatkan pesanan terbaru
        $latestOrders = OrderItem::where('penjual_id', $user->id)
                              ->with(['order', 'order.user', 'product'])
                              ->latest('created_at')
                              ->take(5)
                              ->get()
                              ->map(function($item) {
                                  return [
                                      'id' => $item->id,
                                      'order_id' => $item->order->id,
                                      'order_number' => $item->order->nomor_pesanan,
                                      'product_name' => $item->nama_produk,
                                      'customer_name' => $item->order->user->name,
                                      'status' => $item->order->status,
                                      'status_text' => Order::$statuses[$item->order->status] ?? $item->order->status,
                                      'quantity' => $item->jumlah,
                                      'price' => $item->harga,
                                      'subtotal' => $item->subtotal,
                                      'formatted_subtotal' => 'Rp ' . number_format($item->subtotal, 0, ',', '.'),
                                      'created_at' => $item->created_at->format('d M Y H:i')
                                  ];
                              });

        // Dapatkan informasi toko untuk seller
        // Note: Ini adalah informasi toko (alamat fisik), bukan alamat pengiriman
        $storeInfo = [
            'store_name' => $user->nama_toko ?? 'Belum diatur',
            'store_address' => [
                'full_address' => $user->alamat ?? 'Belum diatur',
                'city' => $user->kota ?? 'Belum diatur',
                'province' => $user->provinsi ?? 'Belum diatur',
                'note' => 'Alamat fisik toko yang dapat dikunjungi pembeli'
            ],
            'phone' => $user->phone ?? 'Belum diatur',
            'description' => $user->deskripsi ?? 'Belum ada deskripsi',
            'opening_hours' => [
                'open' => $user->jam_buka ?? 'Belum diatur',
                'close' => $user->jam_tutup ?? 'Belum diatur'
            ],
            'is_active' => $user->active ?? true,
            'profile_complete' => !empty($user->nama_toko) && !empty($user->alamat) && !empty($user->phone)
        ];
        Log::info('Store info prepared for seller');

            Log::info('Unreplied reviews calculated: ' . $unrepliedReviews);

            // Hitung jumlah pesanan berdasarkan status
            $orderStats = OrderItem::where('penjual_id', $user->id)
                                 ->join('orders', 'order_items.pesanan_id', '=', 'orders.id')
                                 ->select('orders.status', DB::raw('count(*) as count'))
                                 ->groupBy('orders.status')
                                 ->get()
                                 ->pluck('count', 'status')
                                 ->toArray();
            Log::info('Order stats calculated');

            // Lengkapi dengan status yang kosong
            foreach (['menunggu', 'dibayar', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $status) {
                if (!isset($orderStats[$status])) {
                    $orderStats[$status] = 0;
                }
            }

            // Dapatkan data penjualan 7 hari terakhir
            $salesData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $dailySales = OrderItem::where('penjual_id', $user->id)
                                     ->whereHas('order', function($q) use ($date) {
                                         $q->where('status_pembayaran', 'dibayar')
                                           ->whereDate('created_at', $date);
                                     })
                                     ->sum('subtotal');

                $salesData[] = [
                    'date' => Carbon::now()->subDays($i)->format('d M'),
                    'sales' => $dailySales
                ];
            }
            Log::info('Sales data calculated');

            // Dapatkan pesanan terbaru
            $latestOrders = OrderItem::where('penjual_id', $user->id)
                                  ->with(['order', 'order.user', 'product'])
                                  ->latest('created_at')
                                  ->take(5)
                                  ->get()
                                  ->map(function($item) {
                                      return [
                                          'id' => $item->id,
                                          'order_id' => $item->order->id,
                                          'order_number' => $item->order->nomor_pesanan,
                                          'product_name' => $item->nama_produk,
                                          'customer_name' => $item->order->user->name,
                                          'status' => $item->order->status,
                                          'status_text' => Order::$statuses[$item->order->status] ?? $item->order->status,
                                          'quantity' => $item->jumlah,
                                          'price' => $item->harga,
                                          'subtotal' => $item->subtotal,
                                          'formatted_subtotal' => 'Rp ' . number_format($item->subtotal, 0, ',', '.'),
                                          'created_at' => $item->created_at->format('d M Y H:i')
                                      ];
                                  });
            Log::info('Latest orders calculated');

            // Dapatkan informasi toko untuk seller (second instance)
            // Note: Ini adalah informasi toko (alamat fisik), bukan alamat pengiriman
            $storeInfo = [
                'store_name' => $user->nama_toko ?? 'Belum diatur',
                'store_address' => [
                    'full_address' => $user->alamat ?? 'Belum diatur',
                    'city' => $user->kota ?? 'Belum diatur',
                    'province' => $user->provinsi ?? 'Belum diatur',
                    'note' => 'Alamat fisik toko yang dapat dikunjungi pembeli'
                ],
                'phone' => $user->phone ?? 'Belum diatur',
                'description' => $user->deskripsi ?? 'Belum ada deskripsi',
                'opening_hours' => [
                    'open' => $user->jam_buka ?? 'Belum diatur',
                    'close' => $user->jam_tutup ?? 'Belum diatur'
                ],
                'is_active' => $user->active ?? true,
                'profile_complete' => !empty($user->nama_toko) && !empty($user->alamat) && !empty($user->phone)
            ];
            Log::info('Store info prepared for seller (second instance)');

            Log::info('Seller dashboard data prepared successfully');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_revenue' => $totalRevenue,
                    'formatted_total_revenue' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                    'order_count' => $orderCount,
                    'product_count' => $productCount,
                    'out_of_stock_count' => $outOfStockCount,
                    'avg_rating' => $avgRating,
                    'unreplied_reviews' => $unrepliedReviews,
                    'order_stats' => $orderStats,
                    'sales_data' => $salesData,
                    'latest_orders' => $latestOrders,
                    'store_info' => $storeInfo
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in seller dashboard: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get store information for the authenticated seller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStoreInfo(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user->isSeller()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only sellers can access store information.'
                ], 403);
            }

            // Get seller store information - same format as SellerInfoController
            $storeInfo = [
                'nama_toko' => $user->nama_toko ?: $user->name,
                'telepon' => $user->telepon ?: $user->phone,
                'alamat' => $user->alamat,
                'kota' => $user->kota,
                'provinsi' => $user->provinsi,
                'deskripsi' => $user->deskripsi,
                'jam_buka' => $user->jam_buka,
                'jam_tutup' => $user->jam_tutup,
                'status' => $user->status === 'aktif' ? 'aktif' : 'nonaktif',
                'bank_account' => [
                    'bank_name' => $user->bank_name,
                    'account_number' => $user->account_number,
                    'account_holder_name' => $user->account_holder_name,
                ],
                'created_at' => $user->created_at
            ];

            return response()->json([
                'success' => true,
                'message' => 'Store information retrieved successfully',
                'data' => $storeInfo,
                'meta' => [
                    'type' => 'store_info',
                    'context' => 'seller_store_address',
                    'description' => 'Alamat toko untuk kunjungan langsung dan informasi lokasi toko'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting seller store info: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve store information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update seller bank account information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBankAccount(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user->isSeller()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only sellers can update bank account information.'
                ], 403);
            }

            $request->validate([
                'bank_name' => 'required|string|max:100',
                'account_number' => 'required|string|max:50',
                'account_holder_name' => 'required|string|max:100',
            ]);

            $user->update([
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_holder_name' => $request->account_holder_name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank account information updated successfully',
                'data' => [
                    'bank_name' => $user->bank_name,
                    'account_number' => $user->account_number,
                    'account_holder_name' => $user->account_holder_name,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating seller bank account: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update bank account information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

