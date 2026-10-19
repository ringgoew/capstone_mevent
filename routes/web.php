<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EOController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKycController;

// Route::get('/', function () {
//     return view('welcome');
// });


// Route Untuk halaman Utama dan Pendaftaran EO
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/daftar-eo', [EOController::class, 'register'])->name('eo.register');
Route::post('/daftar-eo', [EOController::class, 'store'])->name('eo.register.store');

// Route untuk halaman login admin
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');

// Route untuk halaman dashboard admin (memerlukan autentikasi)
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Pemeriksaan dan Verifikasi KYC EO
Route::get('/admin/kyc', [AdminKycController::class, 'index'])->name('admin.kyc.index');
Route::get('/admin/kyc/{id}', [AdminKycController::class, 'show'])->name('admin.kyc.show');
Route::get('/admin/kyc/{id}/documents/{type}', [AdminKycController::class, 'document'])->name('admin.kyc.document');
Route::post('/admin/kyc/{id}/approve', [AdminKycController::class, 'approve'])->name('admin.kyc.approve');
Route::post('/admin/kyc/{id}/reject', [AdminKycController::class, 'reject'])->name('admin.kyc.reject');
