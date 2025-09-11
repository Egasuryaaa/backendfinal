<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\AddressController;

// Home route - redirect to login first
Route::get('/', function () {
    return redirect('/login');
})->name('home');

// Auth routes (no middleware)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Test endpoint to check auth status
Route::get('/auth-test', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId()
    ]);
});

// Protected fishmarket route - requires authentication
Route::get('/fishmarket', function () {
    return view('fishmarket.index');
})->name('fishmarket')->middleware('auth');

// Product detail route - requires authentication
Route::get('/product/{product}', function ($productId) {
    return view('product.detail', compact('productId'));
})->name('product.detail')->middleware('auth');

// Cart route - requires authentication
Route::get('/cart', function () {
    return view('cart.index');
})->name('cart')->middleware('auth');

// Profile route - requires authentication
Route::get('/profile', function () {
    return view('profile.index');
})->name('profile')->middleware('auth');

// Locations route - requires authentication
Route::get('/locations', [App\Http\Controllers\SellerLocationController::class, 'index'])->name('locations')->middleware('auth');

// API endpoint for locations JSON data
Route::get('/api/locations', [App\Http\Controllers\SellerLocationController::class, 'getLocationsJson'])->name('api.locations')->middleware('auth');

// Web API routes for cart (using web middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/api/cart', [App\Http\Controllers\API\CartController::class, 'index']);
    Route::post('/api/cart', [App\Http\Controllers\API\CartController::class, 'addToCart']);
    Route::put('/api/cart/{cartItem}', [App\Http\Controllers\API\CartController::class, 'updateCartItem']);
    Route::delete('/api/cart/{cartItem}', [App\Http\Controllers\API\CartController::class, 'removeFromCart']);
});

// Checkout route
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout')->middleware('auth');

// Address management routes
Route::get('/addresses', [App\Http\Controllers\Web\AddressController::class, 'index'])->name('addresses.index')->middleware('auth');

// Address resource routes
Route::middleware(['auth'])->group(function () {
    Route::get('/addresses/create', [App\Http\Controllers\Web\AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [App\Http\Controllers\Web\AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [App\Http\Controllers\Web\AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [App\Http\Controllers\Web\AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [App\Http\Controllers\Web\AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/addresses/{address}/set-main', [App\Http\Controllers\Web\AddressController::class, 'setMain'])->name('addresses.setMain');
});

// Web API routes for addresses (using web middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/api/addresses', [App\Http\Controllers\Web\AddressController::class, 'index']);
    Route::post('/api/addresses', [App\Http\Controllers\Web\AddressController::class, 'store']);
    Route::put('/api/addresses/{address}', [App\Http\Controllers\Web\AddressController::class, 'update']);
    Route::delete('/api/addresses/{address}', [App\Http\Controllers\Web\AddressController::class, 'destroy']);
    Route::put('/api/addresses/{address}/set-as-main', [App\Http\Controllers\Web\AddressController::class, 'setAsMain']);
    
    // Orders API
    Route::post('/api/orders/checkout', [App\Http\Controllers\API\OrderController::class, 'checkout']);
});

// Protected routes (with Laravel auth middleware)
Route::middleware(['auth'])->group(function () {
    // Add more protected routes here as needed
    // Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    // Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    
    // Appointment routes
    Route::get('/appointments/create', [App\Http\Controllers\AppointmentViewController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [App\Http\Controllers\AppointmentViewController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/history', [App\Http\Controllers\AppointmentViewController::class, 'history'])->name('appointments.history');
    Route::get('/appointments/{appointment}', [App\Http\Controllers\AppointmentViewController::class, 'show'])->name('appointments.show');
    Route::put('/appointments/{appointment}/update-status', [App\Http\Controllers\AppointmentViewController::class, 'updateStatus'])->name('appointments.update-status');
});

