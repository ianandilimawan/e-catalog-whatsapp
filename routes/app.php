<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\ProductController;
use App\Http\Controllers\App\StoreController;
use App\Http\Controllers\App\StatsController;
use App\Http\Controllers\App\AccountController;

Route::get('/', function () {
    return redirect()->route('app.dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Product management
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

// Store settings
Route::get('/store', [StoreController::class, 'edit'])->name('store.edit');
Route::put('/store', [StoreController::class, 'update'])->name('store.update');

// Statistics
Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');

// Account & Profile management
Route::get('/account', [AccountController::class, 'index'])->name('account.index');
Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.update_profile');
Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.update_password');
