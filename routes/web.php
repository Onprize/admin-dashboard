<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryPartnerController;
use App\Http\Controllers\SettingsController;

// Redirect root to dashboard or login
Route::get('/', function () {
    return session('api_token') ? redirect('/dashboard') : redirect('/login');
});

// Auth routes (guest middleware)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth.api')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.update-status');

    // Restaurants
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');
    Route::put('/restaurants/{id}/approve', [RestaurantController::class, 'approve'])->name('restaurants.approve');
    Route::put('/restaurants/{id}/reject', [RestaurantController::class, 'reject'])->name('restaurants.reject');
    Route::put('/restaurants/{id}/toggle-status', [RestaurantController::class, 'toggleStatus'])->name('restaurants.toggle-status');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // Delivery Partners
    Route::get('/delivery-partners', [DeliveryPartnerController::class, 'index'])->name('delivery-partners.index');
    Route::get('/delivery-partners/{id}', [DeliveryPartnerController::class, 'show'])->name('delivery-partners.show');
    Route::put('/delivery-partners/{id}/verify', [DeliveryPartnerController::class, 'verify'])->name('delivery-partners.verify');
    Route::put('/delivery-partners/{id}/reject', [DeliveryPartnerController::class, 'reject'])->name('delivery-partners.reject');
    Route::put('/delivery-partners/{id}/toggle-status', [DeliveryPartnerController::class, 'toggleStatus'])->name('delivery-partners.toggle-status');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/{key}', [SettingsController::class, 'update'])->name('settings.update');
});
