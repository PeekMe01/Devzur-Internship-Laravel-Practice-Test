<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);

Route::middleware([CheckAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('adminDashboard');
    Route::get('/admin/products', [AdminController::class, 'products'])->name('adminProducts');
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('adminCategories');
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('adminOrders');
    Route::get('/admin/payments', [AdminController::class, 'payments'])->name('adminPayments');
    // Other routes that require authentication
});