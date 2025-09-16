<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - API ONLY APPROACH
|--------------------------------------------------------------------------
| IwakMart - E-commerce Platform untuk Marketplace Ikan
| Semua routes menggunakan API endpoints untuk konsistensi dan kemudahan
| Authentication menggunakan Laravel Sanctum
|--------------------------------------------------------------------------
*/

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

// Home route - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Authentication pages (views only)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Test page for fish farm creation
Route::get('/fish-farm-test', function () {
    return view('fish-farm-test');
})->name('fish-farm-test');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Auth status check route (untuk frontend authentication check)
Route::get('/auth-test', function () {
    // Check session auth first
    if (Auth::check()) {
        return response()->json([
            'authenticated' => true,
            'user' => Auth::user(),
            'method' => 'session'
        ]);
    }
    
    // Check Sanctum token auth
    if (Auth::guard('sanctum')->check()) {
        return response()->json([
            'authenticated' => true,
            'user' => Auth::guard('sanctum')->user(),
            'method' => 'token'
        ]);
    }
    
    // Check for token in cookie
    $token = request()->cookie('auth_token');
    if ($token) {
        request()->headers->set('Authorization', 'Bearer ' . $token);
        if (Auth::guard('sanctum')->check()) {
            return response()->json([
                'authenticated' => true,
                'user' => Auth::guard('sanctum')->user(),
                'method' => 'cookie_token'
            ]);
        }
    }
    
    return response()->json([
        'authenticated' => false,
        'user' => null,
        'method' => 'none'
    ]);
})->name('auth.test');

// ============================================================================
// PROTECTED ROUTES (Semua menggunakan API endpoints dari routes/api.php)
// ============================================================================

Route::middleware(['hybrid.auth'])->group(function () {

    // Main pages (views yang consume API)
    Route::get('/fishmarket', function () {
        return view('fishmarket.index');
    })->name('fishmarket');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    // Product pages
    Route::get('/products', function () {
        return view('products.index');
    })->name('products');

    Route::get('/product/{productId}', function ($productId) {
        return view('product.detail', compact('productId'));
    })->name('product.detail');

    // Cart page
    Route::get('/cart', function () {
        return view('cart.index');
    })->name('cart');

    // Checkout & Order pages
    Route::get('/checkout', function () {
        return view('checkout.index');
    })->name('checkout');

    Route::get('/orders', function () {
        return view('orders.index');
    })->name('orders');

    Route::get('/order/{orderId}', function ($orderId) {
        return view('orders.detail', compact('orderId'));
    })->name('order.detail');

    // Address pages
    Route::get('/addresses', function () {
        return view('addresses.index');
    })->name('addresses');

    Route::get('/addresses/create', function () {
        return view('addresses.create');
    })->name('addresses.create');

    Route::get('/addresses/{addressId}/edit', function ($addressId) {
        return view('addresses.edit', compact('addressId'));
    })->name('addresses.edit');

    // Appointment pages
    Route::get('/appointments', function () {
        return view('appointments.index');
    })->name('appointments');

    Route::get('/appointments/create', function () {
        return view('appointments.create');
    })->name('appointments.create');

    Route::get('/appointment/{appointmentId}', function ($appointmentId) {
        return view('appointments.detail', compact('appointmentId'));
    })->name('appointment.detail');

    // Seller pages
    Route::get('/seller/dashboard', function () {
        return view('seller.dashboard');
    })->name('seller.dashboard');

    Route::get('/seller/products', function () {
        return view('seller.products');
    })->name('seller.products');

    Route::get('/seller/orders', function () {
        return view('seller.orders');
    })->name('seller.orders');

    Route::get('/seller/appointments', function () {
        return view('seller.appointments');
    })->name('seller.appointments');

    Route::get('/seller/locations', function () {
        return view('seller.locations');
    })->name('seller.locations');

    // Fish Farm Management (for farmers)
    Route::get('/fish-farms', function () {
        return view('fish-farms.index');
    })->name('fish-farms.index');

    Route::get('/fish-farms/create', function () {
        return view('fish-farms.create');
    })->name('fish-farms.create');

    Route::get('/fish-farms/{id}', function ($id) {
        return view('fish-farms.show', ['fishFarmId' => $id]);
    })->name('fish-farms.show');

    Route::get('/fish-farms/{id}/edit', function ($id) {
        return view('fish-farms.edit', ['fishFarmId' => $id]);
    })->name('fish-farms.edit');

    // Collector Management (for collectors)
    Route::get('/collectors', function () {
        return view('collectors.index');
    })->name('collectors.index');

    Route::get('/collectors/create', function () {
        return view('collectors.create');
    })->name('collectors.create');

    Route::get('/collectors/{id}', function ($id) {
        return view('collectors.show', ['collectorId' => $id]);
    })->name('collectors.show');

    Route::get('/collectors/{id}/edit', function ($id) {
        return view('collectors.edit', ['collectorId' => $id]);
    })->name('collectors.edit');

    // Fish Farm Appointments
    Route::get('/fish-farm-appointments', function () {
        return view('appointments.fish-farms');
    })->name('fish-farm-appointments');

    // Collector Appointments
    Route::get('/collector-appointments', function () {
        return view('appointments.collectors');
    })->name('collector-appointments');

});

/*
|--------------------------------------------------------------------------
| SEMUA BUSINESS LOGIC MENGGUNAKAN API ENDPOINTS
|--------------------------------------------------------------------------
| Authentication: /api/auth/login, /api/auth/register, /api/auth/logout
| Products: /api/products/*
| Cart: /api/cart/*
| Orders: /api/orders/*
| Addresses: /api/addresses/*
| Appointments: /api/appointments/*
| Seller: /api/seller/*
|
| Semua endpoint ada di routes/api.php dengan auth:sanctum middleware
|--------------------------------------------------------------------------
*/