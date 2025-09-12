<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Appointment;
use App\Models\Review;
use Carbon\Carbon;

class SellerController extends Controller
{
<<<<<<< HEAD
    /**z
=======
    /**
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
     * Mendapatkan data dashboard untuk penjual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        // Hitung total pendapatan
        $totalRevenue = OrderItem::where('penjual_id', $user->id)
                               ->whereHas('order', function($q) {
                                   $q->where('status_pembayaran', 'dibayar');
                               })
                               ->sum('subtotal');
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        // Hitung jumlah pesanan
        $orderCount = OrderItem::where('penjual_id', $user->id)
                             ->distinct('pesanan_id')
                             ->count('pesanan_id');
<<<<<<< HEAD
        
        // Hitung jumlah produk
        $productCount = Product::where('penjual_id', $user->id)->count();
        
=======

        // Hitung jumlah produk
        $productCount = Product::where('penjual_id', $user->id)->count();

>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        // Hitung jumlah produk stok habis
        $outOfStockCount = Product::where('penjual_id', $user->id)
                                ->where('stok', 0)
                                ->count();
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        // Hitung rating rata-rata
        $avgRating = Review::whereHas('product', function($q) use ($user) {
                           $q->where('penjual_id', $user->id);
                       })
                       ->avg('rating');
<<<<<<< HEAD
        
        $avgRating = round($avgRating, 1);
        
=======

        $avgRating = round($avgRating, 1);

>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        // Hitung jumlah ulasan yang belum dibalas
        $unrepliedReviews = Review::whereHas('product', function($q) use ($user) {
                                 $q->where('penjual_id', $user->id);
                             })
                             ->doesntHave('reviewReply')
                             ->count();
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        // Hitung jumlah pesanan berdasarkan status
        $orderStats = OrderItem::where('penjual_id', $user->id)
                             ->join('orders', 'order_items.pesanan_id', '=', 'orders.id')
                             ->select('orders.status', DB::raw('count(*) as count'))
                             ->groupBy('orders.status')
                             ->get()
                             ->pluck('count', 'status')
                             ->toArray();
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        // Lengkapi dengan status yang kosong
        foreach (['menunggu', 'dibayar', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $status) {
            if (!isset($orderStats[$status])) {
                $orderStats[$status] = 0;
            }
        }
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
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
<<<<<<< HEAD
            
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
            $salesData[] = [
                'date' => Carbon::now()->subDays($i)->format('d M'),
                'sales' => $dailySales
            ];
        }
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
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
<<<<<<< HEAD
        
=======
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        // Dapatkan janji temu akan datang
        $upcomingAppointments = Appointment::where('penjual_id', $user->id)
                                       ->where('tanggal_janji', '>=', now())
                                       ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                                       ->with(['buyer', 'sellerLocation'])
                                       ->orderBy('tanggal_janji')
                                       ->take(5)
                                       ->get()
                                       ->map(function($item) {
                                           return [
                                               'id' => $item->id,
                                               'customer_name' => $item->buyer->name,
                                               'location_name' => $item->sellerLocation->nama_usaha,
                                               'date' => Carbon::parse($item->tanggal_janji)->format('d M Y'),
                                               'time' => Carbon::parse($item->tanggal_janji)->format('H:i'),
                                               'status' => $item->status,
                                               'status_text' => Appointment::$statuses[$item->status] ?? $item->status,
                                               'purpose' => $item->tujuan
                                           ];
                                       });

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
                'upcoming_appointments' => $upcomingAppointments
            ]
        ]);
    }
<<<<<<< HEAD
}
=======

    /**
     * Mendapatkan profil seller.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'roles' => $user->getRoleNames(),
                'created_at' => $user->created_at->format('d M Y'),
                'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('d M Y H:i') : null,
            ]
        ]);
    }

    /**
     * Update profil seller.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
        ]);
    }

    /**
     * Mendapatkan statistik analytics untuk seller.
     */
    public function analytics(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Data penjualan bulanan (12 bulan terakhir)
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyRevenue = OrderItem::where('penjual_id', $user->id)
                                     ->whereHas('order', function($q) use ($date) {
                                         $q->where('status_pembayaran', 'dibayar')
                                           ->whereYear('created_at', $date->year)
                                           ->whereMonth('created_at', $date->month);
                                     })
                                     ->sum('subtotal');

            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'revenue' => $monthlyRevenue,
                'formatted_revenue' => 'Rp ' . number_format($monthlyRevenue, 0, ',', '.')
            ];
        }

        // Produk terlaris
        $topProducts = OrderItem::where('penjual_id', $user->id)
                                ->select('nama_produk', DB::raw('SUM(jumlah) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
                                ->groupBy('nama_produk')
                                ->orderBy('total_sold', 'desc')
                                ->take(5)
                                ->get()
                                ->map(function($item) {
                                    return [
                                        'product_name' => $item->nama_produk,
                                        'total_sold' => $item->total_sold,
                                        'total_revenue' => $item->total_revenue,
                                        'formatted_revenue' => 'Rp ' . number_format($item->total_revenue, 0, ',', '.')
                                    ];
                                });

        // Customer terbanyak
        $topCustomers = OrderItem::where('penjual_id', $user->id)
                                 ->join('orders', 'order_items.pesanan_id', '=', 'orders.id')
                                 ->join('users', 'orders.user_id', '=', 'users.id')
                                 ->select('users.name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(order_items.subtotal) as total_spent'))
                                 ->groupBy('users.id', 'users.name')
                                 ->orderBy('order_count', 'desc')
                                 ->take(5)
                                 ->get()
                                 ->map(function($item) {
                                     return [
                                         'customer_name' => $item->name,
                                         'order_count' => $item->order_count,
                                         'total_spent' => $item->total_spent,
                                         'formatted_spent' => 'Rp ' . number_format($item->total_spent, 0, ',', '.')
                                     ];
                                 });

        return response()->json([
            'success' => true,
            'data' => [
                'monthly_data' => $monthlyData,
                'top_products' => $topProducts,
                'top_customers' => $topCustomers
            ]
        ]);
    }

    /**
     * Get seller messages/conversations
     */
    public function getMessages(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get conversations where seller is involved
        $conversations = \App\Models\Message::where('penerima_id', $user->id)
                        ->orWhere('pengirim_id', $user->id)
                        ->with(['sender', 'recipient'])
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->groupBy(function($message) use ($user) {
                            return $message->pengirim_id == $user->id
                                ? $message->penerima_id
                                : $message->pengirim_id;
                        })
                        ->map(function($messages) use ($user) {
                            $lastMessage = $messages->first();
                            $otherUser = $lastMessage->pengirim_id == $user->id
                                ? $lastMessage->recipient
                                : $lastMessage->sender;

                            $unreadCount = $messages->where('penerima_id', $user->id)
                                                  ->whereNull('dibaca_pada')
                                                  ->count();

                            return [
                                'id' => $otherUser->id,
                                'other_user' => [
                                    'id' => $otherUser->id,
                                    'name' => $otherUser->name,
                                    'email' => $otherUser->email,
                                ],
                                'last_message' => [
                                    'id' => $lastMessage->id,
                                    'isi' => $lastMessage->isi,
                                    'created_at' => $lastMessage->created_at,
                                    'is_sender' => $lastMessage->pengirim_id == $user->id,
                                ],
                                'unread_count' => $unreadCount,
                            ];
                        })
                        ->values();

        return response()->json([
            'success' => true,
            'data' => $conversations
        ]);
    }

    /**
     * Get seller product reviews
     */
    public function getReviews(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get reviews for seller's products
        $reviews = \App\Models\Review::whereHas('product', function($query) use ($user) {
                        $query->where('penjual_id', $user->id);
                    })
                    ->with(['user', 'product', 'reviewReply', 'reviewReply.user'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Reply to a review
     */
    public function replyToReview(Request $request, $reviewId)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'comment' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!$user->hasRole('seller')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $review = \App\Models\Review::find($reviewId);
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan tidak ditemukan'
            ], 404);
        }

        // Check if this review is for seller's product
        $product = \App\Models\Product::find($review->produk_id);
        if (!$product || $product->penjual_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - bukan produk Anda'
            ], 403);
        }

        // Create or update reply
        $reply = \App\Models\ReviewReply::updateOrCreate(
            ['review_id' => $review->id],
            [
                'user_id' => $user->id,
                'comment' => $request->comment
            ]
        );

        // Create notification for customer
        $review->user->notifications()->create([
            'judul' => 'Balasan Ulasan',
            'isi' => "{$user->name} membalas ulasan Anda untuk produk {$product->nama}.",
            'jenis' => 'ulasan',
            'tautan' => '/produk/' . $product->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil dikirim',
            'data' => $reply
        ]);
    }
}
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
