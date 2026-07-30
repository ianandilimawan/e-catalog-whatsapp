<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LaravelLogController;
use App\Http\Controllers\SettingController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Public Registration
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // OTP routes
    Route::get('/login/otp', [AuthController::class, 'showOtpForm'])->name('login.otp');
    Route::post('/login/otp', [AuthController::class, 'verifyOtp'])->name('login.otp.post');
    Route::post('/login/otp/resend', [AuthController::class, 'resendOtp'])->name('login.otp.resend');

    Route::get('/register', [\App\Http\Controllers\RegisterController::class, 'show'])->name('register');
    Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'store'])->name('register.post');
});

// Onboarding (must be logged in but no store yet)
Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding/store', [\App\Http\Controllers\OnboardingController::class, 'show'])->name('onboarding.store');
    Route::post('/onboarding/store', [\App\Http\Controllers\OnboardingController::class, 'store'])->name('onboarding.store.post');
});

// Slug availability check (public, lightweight)
Route::get('/check-slug', function (\Illuminate\Http\Request $request) {
    $slug = \Illuminate\Support\Str::slug($request->query('slug', ''));
    if (!$slug || strlen($slug) < 3) {
        return response()->json(['available' => false, 'suggestions' => []]);
    }
    $exists = \App\Models\Store::where('slug', $slug)->exists();
    $suggestions = [];
    if ($exists) {
        foreach (['-toko', '-store', '-shop', '-id'] as $suffix) {
            $candidate = $slug . $suffix;
            if (!\App\Models\Store::where('slug', $candidate)->exists()) {
                $suggestions[] = $candidate;
                if (count($suggestions) >= 3) break;
            }
        }
    }
    return response()->json(['available' => !$exists, 'suggestions' => $suggestions]);
})->name('check-slug');

// Admin Authentication routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Protected admin routes
    Route::middleware(['auth', 'ensure.store'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Resource routes
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);

        // Activity Logs routes
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);

        // Laravel Logs routes
        Route::get('laravel-logs', [LaravelLogController::class, 'index'])->name('laravel-logs.index');
        Route::get('laravel-logs/{fileName}', [LaravelLogController::class, 'show'])->name('laravel-logs.show');
        Route::delete('laravel-logs/{fileName}/clear', [LaravelLogController::class, 'clear'])->name('laravel-logs.clear');
        Route::delete('laravel-logs/{fileName}', [LaravelLogController::class, 'destroy'])->name('laravel-logs.destroy');

        // Settings routes
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        // Profile routes
        Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
        Route::put('profile/update', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('profile/check-password', [\App\Http\Controllers\ProfileController::class, 'checkPassword'])->name('profile.check-password');

        // Test Error Pages (only in non-production)
        if (app()->environment(['local', 'staging', 'development'])) {
            Route::get('test-error/{code}', function ($code) {
                $allowedCodes = [404, 500, 403, 419, 503];
                if (!in_array($code, $allowedCodes)) {
                    abort(404, 'Error code not found');
                }
                abort((int)$code, 'Test error page');
            })->name('test.error');
        }
    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'ensure.store', 'web'])->group(function () {
    // Store routes
    Route::resource('stores', \App\Http\Controllers\StoreController::class);
    // Category routes
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    // Product routes
    Route::delete('products/{product}/images/{image}', [\App\Http\Controllers\ProductController::class, 'deleteImage'])->name('products.images.destroy');
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    // [ADMIN_ROUTES_MARKER]
});

// Public Catalog Routes
Route::post('/{slug}/track-product-view/{product}', [App\Http\Controllers\CatalogController::class, 'trackProductView'])->name('catalog.track-product-view');
Route::post('/{slug}/track-wa-click', [App\Http\Controllers\CatalogController::class, 'trackWaClick'])->name('catalog.track-wa-click');
Route::get('/{slug}', [App\Http\Controllers\CatalogController::class, 'show'])->name('catalog.show');
