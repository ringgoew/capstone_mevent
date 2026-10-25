<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventVerificationController;

// EO Controllers
use App\Http\Controllers\EO\EOAuthController;
use App\Http\Controllers\EO\EOKycController;
use App\Http\Controllers\EO\EODashboardController;
use App\Http\Controllers\EO\EventController;

// Payment Controller
use App\Http\Controllers\PaymentController;


// PUBLIC ROUTES
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{slug}', [EventController::class, 'show'])
    ->name('events.show');

Route::middleware(['guest'])->group(function () {
    Route::get('/eo/login', [EOAuthController::class, 'login'])->name('eo.login');
    Route::post('/eo/login', [EOAuthController::class, 'authenticate'])->name('eo.login.authenticate');

    Route::get('/daftar-eo', [EOAuthController::class, 'register'])->name('eo.register');
    Route::post('/daftar-eo', [EOAuthController::class, 'store'])->name('eo.register.store');
});

Route::get('/daftar-eo/otp', [EOKycController::class, 'otp'])->name('eo.otp');
Route::post('/daftar-eo/otp', [EOKycController::class, 'verifyOtp'])->name('eo.otp.verify');

Route::get('/daftar-eo/kyc', [EOKycController::class, 'kyc'])->name('eo.kyc');
Route::post('/daftar-eo/kyc', [EOKycController::class, 'submitKyc'])->name('eo.kyc.submit');
Route::get('/daftar-eo/pending', [EOKycController::class, 'pending'])->name('eo.pending');


// EO ROUTES
Route::middleware([])->group(function () {
    Route::get('/eo/dashboard', [EODashboardController::class, 'dashboard'])->name('eo.dashboard');

    Route::get('/eo/events', [EventController::class, 'index'])->name('eo.events.index');
    Route::get('/eo/events/create', [EventController::class, 'create'])->name('eo.events.create');
    Route::post('/eo/events', [EventController::class, 'store'])->name('eo.events.store');
    Route::post('/eo/events/{id}/submit', [EventController::class, 'submitForReview'])->name('eo.events.submit');

    Route::post('/eo/logout', [EOAuthController::class, 'logout'])->name('eo.logout');
});


// ADMIN ROUTES
Route::middleware(['guest'])->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/admin/authenticate', [AdminAuthController::class, 'authenticate'])->name('admin.authenticate');
});

Route::middleware([])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/kyc', [AdminKycController::class, 'index'])->name('admin.kyc.index');
    Route::get('/admin/kyc/{id}', [AdminKycController::class, 'show'])->name('admin.kyc.show');
    Route::get('/admin/kyc/{id}/documents/{type}', [AdminKycController::class, 'document'])->name('admin.kyc.document');
    Route::post('/admin/kyc/{id}/approve', [AdminKycController::class, 'approve'])->name('admin.kyc.approve');
    Route::post('/admin/kyc/{id}/reject', [AdminKycController::class, 'reject'])->name('admin.kyc.reject');

    Route::get('/admin/event-verification', [AdminEventVerificationController::class, 'index'])->name('admin.event-verification.index');
    Route::get('/admin/event-verification/{id}', [AdminEventVerificationController::class, 'show'])->name('admin.event-verification.show');
    Route::post('/admin/event-verification/{id}/publish', [AdminEventVerificationController::class, 'publish'])->name('admin.event-verification.publish');
    Route::post('/admin/event-verification/{id}/reject', [AdminEventVerificationController::class, 'reject'])->name('admin.event-verification.reject');

    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});


// PAYMENT ROUTES
Route::middleware([])->group(function () {
    Route::get('/payment/checkout', [PaymentController::class, 'checkout'])->name('checkout');
});
