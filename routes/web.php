<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Web\PaymentPageController;

/*
|--------------------------------------------------------------------------
| Web Routes - API ONLY APPROACH
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| IwakMart - E-commerce Platform untuk Marketplace Ikan
| Semua routes menggunakan API endpoints untuk konsistensi dan kemudahan
| Authentication menggunakan Laravel Sanctum
|--------------------------------------------------------------------------
*/

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

// Home route - redirect to fish market (public access)
Route::get('/', function () {
    return redirect()->route('fishmarket');
})->name('home');

// Route List Documentation
Route::get('/route-list', function () {
    return view('route-list');
})->name('route-list');

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

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

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

// Payment result pages - public access (no auth required untuk redirect dari Xendit)
Route::get('/payment/success', [PaymentPageController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [PaymentPageController::class, 'failed'])->name('payment.failed');
Route::get('/payment/pending', [PaymentPageController::class, 'pending'])->name('payment.pending');

// ============================================================================
// PUBLIC ACCESS ROUTES - Fish Market & Product Browsing
// ============================================================================

// Fish Market - Main page (public access, no login required)
Route::get('/fishmarket', function () {
    return view('fishmarket.index');
})->name('fishmarket');

// Product browsing - public access
Route::get('/products', function () {
    return view('products.index');
})->name('products');

// Product detail view - public access
Route::get('/product/{productId}', function ($productId) {
    return view('product.detail', compact('productId'));
})->name('product.detail');

// ============================================================================
// PROTECTED ROUTES (Semua menggunakan API endpoints dari routes/api.php)
// ============================================================================

Route::middleware(['hybrid.auth'])->group(function () {

    // Protected pages that require authentication
    Route::get('/locations', function () {
        return view('locations.index');
    })->name('locations');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

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

    Route::get('/orders/{orderId}', function ($orderId) {
        return view('orders.detail', compact('orderId'));
    })->name('orders.show');

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

    Route::get('/seller/profile', function () {
        return view('seller.profile');
    })->name('seller.profile');

    // Fish Farm Management (for farmers)
    Route::get('/fish-farms', function () {
        return view('fish-farms.index');
    })->name('fish-farms.index');

    Route::get('/fish-farms/dashboard', function () {
        return view('fish-farms.dashboard');
    })->name('fish-farms.dashboard');

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
