<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\FishFarmController;
use App\Http\Controllers\API\CollectorController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\SellerController;
use App\Http\Controllers\API\SellerInfoController;
use App\Http\Controllers\API\SellerLocationController;
use App\Http\Controllers\API\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// CSRF Cookie route
Route::get('/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Test route
Route::get('/test-seller', function () {
    return response()->json(['message' => 'Test seller route working']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/search/{keyword}', [ProductController::class, 'search']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/categories/{category}/products', [ProductController::class, 'byCategory']);

// Reviews (public read)
Route::get('/products/{product}/reviews', [ReviewController::class, 'productReviews']);

// Seller info (public)
Route::get('/sellers/from-product', [SellerInfoController::class, 'getFromProduct']);
Route::get('/sellers/{sellerId}', [SellerInfoController::class, 'show']);

// Protected seller bank account (requires auth and valid order)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/sellers/{sellerId}/bank-account', [SellerInfoController::class, 'getBankAccount']);
});

// Payment methods (public)
Route::get('/payment/methods', [PaymentController::class, 'getPaymentMethods']);
Route::get('/payment/config', [PaymentController::class, 'getPaymentConfig']);

// Seller locations (public)
Route::get('/seller-locations', [SellerLocationController::class, 'index']);
Route::get('/seller-locations/{sellerLocation}', [SellerLocationController::class, 'show']);

// Webhook endpoints (no auth required)
Route::post('/webhooks/xendit', [PaymentController::class, 'handleWebhook']);

// Alternative route for seller registration (bypass stateful middleware)
Route::post('/register-seller', [AuthController::class, 'registerAsSeller'])->middleware('auth:sanctum');

// Protected routes with API authentication
    Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/user/register-seller', [AuthController::class, 'registerAsSeller']);
    Route::get('/user/profile', [AuthController::class, 'profile']);
    Route::put('/user/store-info', [AuthController::class, 'updateStoreInfo']);

    // Appointments moved below to avoid duplication

    // Addresses
    Route::apiResource('addresses', AddressController::class);
    Route::put('/addresses/{address}/set-as-main', [AddressController::class, 'setAsMain']);

    // Cart routes moved to web.php for session authentication
    // Route::get('/cart', [CartController::class, 'index']);
    // Route::post('/cart', [CartController::class, 'addToCart']);
    // Route::put('/cart/{cartItem}', [CartController::class, 'updateCartItem']);
    // Route::delete('/cart/{cartItem}', [CartController::class, 'removeFromCart']);

    // Cart routes - restored for API usage
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'addToCart']);
    Route::put('/cart/{cartItem}', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/{cartItem}', [CartController::class, 'removeFromCart']);
    Route::delete('/cart', [CartController::class, 'clearCart']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/statistics', [OrderController::class, 'statistics']);
    Route::post('/orders/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/orders/{order}/items', [OrderController::class, 'items']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelOrder']);
    Route::post('/orders/{order}/complete', [OrderController::class, 'completeOrder']);

    // Manual payment features
    Route::get('/orders/{order}/bank-account', [OrderController::class, 'getOrderWithBankAccount']);
    Route::post('/orders/{order}/payment-proof', [OrderController::class, 'uploadPaymentProof']);
    Route::post('/orders/{order}/verify-payment', [OrderController::class, 'verifyPayment']);
    Route::post('/orders/check-expired', [OrderController::class, 'checkExpiredOrders']);

    // Payments
    Route::post('/payments', [PaymentController::class, 'createPayment']);
    Route::get('/payments/{paymentId}/status', [PaymentController::class, 'getPaymentStatus']);
    Route::post('/payments/{paymentId}/cancel', [PaymentController::class, 'cancelPayment']);

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // Fish Farm routes
    Route::apiResource('fish-farms', FishFarmController::class);
    Route::get('/fish-farms/{id}/available-collectors', [FishFarmController::class, 'getAvailableCollectors']);
    Route::post('/fish-farms/{id}/appointments', [FishFarmController::class, 'createAppointment']);

    // Collector routes
    Route::get('/collectors/nearest', [CollectorController::class, 'getNearestCollectors']);
    Route::get('/collectors/debug', function() {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return response()->json([
            'authenticated' => !!$user,
            'user_id' => $user?->id,
            'user_role' => $user?->role,
            'has_coordinates' => $user?->hasCoordinates() ?? false,
            'coordinates' => $user?->getCoordinates(),
            'is_pemilik_tambak' => $user?->isPemilikTambak() ?? false,
        ]);
    });
    Route::apiResource('collectors', CollectorController::class);
    Route::get('/collectors/statistics', [CollectorController::class, 'getCurrentUserStatistics']);
    Route::get('/collectors/{id}/nearby-fish-farms', [CollectorController::class, 'getNearbyFishFarms']);
    Route::get('/collectors/{id}/pending-appointments', [CollectorController::class, 'getPendingAppointments']);
    Route::put('/collectors/{id}/appointments/{appointmentId}', [CollectorController::class, 'handleAppointment']);
    Route::put('/collectors/{id}/appointments/{appointmentId}/complete', [CollectorController::class, 'completeAppointment']);
    Route::get('/collectors/{id}/statistics', [CollectorController::class, 'getStatistics']);

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);

    // Collector Appointments (untuk pemilik_tambak dan pengepul) - Must come before generic routes
    Route::post('/appointments/collector', [AppointmentController::class, 'createCollectorAppointment']);
    Route::get('/appointments/collector', [AppointmentController::class, 'getCollectorAppointments']);
    Route::put('/appointments/collector/{id}/cancel', [AppointmentController::class, 'cancelCollectorAppointment']);

    // Pengepul specific routes
    Route::put('/appointments/collector/{id}/respond', [AppointmentController::class, 'respondToAppointment']);
    Route::put('/appointments/collector/{id}/complete', [AppointmentController::class, 'completeAppointment']);

    // Generic appointment routes - Must come after specific routes
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show']);
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);
    Route::put('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);

    Route::post('/appointments/{id}/whatsapp-summary', [AppointmentController::class, 'sendWhatsAppSummary']);

    // Messages
    Route::get('/messages/{userId}', [MessageController::class, 'getConversation']);
    Route::post('/messages', [MessageController::class, 'sendMessage']);
    Route::put('/messages/{message}/read', [MessageController::class, 'markAsRead']);
    Route::get('/conversations', [MessageController::class, 'getConversations']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{notification}', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);

    // Test seller auth route
    Route::get('/test-seller-auth', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'is_seller' => $user->isSeller()
        ]);
    });

    // Seller routes - role checking moved to controller level
    Route::prefix('seller')->group(function () {
        // Dashboard
        Route::get('/dashboard', [SellerController::class, 'dashboard']);

        // Store Info
        Route::get('/store-info', [SellerController::class, 'getStoreInfo']);

        // Bank Account Management
        Route::put('/bank-account', [SellerController::class, 'updateBankAccount']);

        // Products
        Route::get('/products', [ProductController::class, 'sellerProducts']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // Orders
        Route::get('/orders', [OrderController::class, 'sellerOrders']);
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);

        // Reviews
        Route::post('/reviews/{review}/reply', [ReviewController::class, 'reply']);

        // Appointments
        Route::get('/appointments', [AppointmentController::class, 'sellerAppointments']);

        // Locations
        Route::get('/locations', [SellerLocationController::class, 'sellerLocations']);
        Route::post('/locations', [SellerLocationController::class, 'store']);
        Route::put('/locations/{sellerLocation}', [SellerLocationController::class, 'update']);
        Route::delete('/locations/{sellerLocation}', [SellerLocationController::class, 'destroy']);
    });
});
