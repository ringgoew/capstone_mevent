<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\AdminDashboardController;

use App\Http\Controllers\EO\EOAuthController;
use App\Http\Controllers\EO\EOKycController;
use App\Http\Controllers\EO\EODashboardController;
use App\Http\Controllers\EO\EventController;

// --- PUBLIC ROUTE ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// 1. Auth & Register (Guest)
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

Route::middleware([])->group(function () {
    Route::get('/eo/dashboard', [EODashboardController::class, 'dashboard'])->name('eo.dashboard');

    Route::get('/eo/events', [EventController::class, 'index'])->name('eo.events.index');
    Route::get('/eo/events/create', [EventController::class, 'create'])->name('eo.events.create');
    Route::post('/eo/events', [EventController::class, 'store'])->name('eo.events.store');
    Route::post('/eo/events/{id}/submit', [EventController::class, 'submitForReview'])->name('eo.events.submit');
    
    Route::post('/eo/logout', [EOAuthController::class, 'logout'])->name('eo.logout');
});


// 1. Auth Admin (Guest)
Route::middleware(['guest'])->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/admin/authenticate', [AdminAuthController::class, 'authenticate'])->name('admin.authenticate');
});

// 2. Fitur Utama Admin (Wajib Login)
Route::middleware([])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/kyc', [AdminKycController::class, 'index'])->name('admin.kyc.index');
    Route::get('/admin/kyc/{id}', [AdminKycController::class, 'show'])->name('admin.kyc.show');
    Route::get('/admin/kyc/{id}/documents/{type}', [AdminKycController::class, 'document'])->name('admin.kyc.document');
    Route::post('/admin/kyc/{id}/approve', [AdminKycController::class, 'approve'])->name('admin.kyc.approve');
    Route::post('/admin/kyc/{id}/reject', [AdminKycController::class, 'reject'])->name('admin.kyc.reject');

    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});