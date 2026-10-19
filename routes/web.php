<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EOController;
use App\Http\Controllers\EventController;

use App\Http\Controllers\AdminController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/daftar-eo', [EOController::class, 'register'])->name('eo.register');
Route::post('/daftar-eo', [EOController::class, 'store'])->name('eo.register.store');
Route::get('/daftar-eo/otp', [EOController::class, 'otp'])->name('eo.otp');
Route::post('/daftar-eo/otp', [EOController::class, 'verifyOtp']) ->name('eo.otp.verify');

Route::get('/daftar-eo/kyc', [EOController::class, 'kyc'])->name('eo.kyc');
Route::post('/daftar-eo/kyc', [EOController::class, 'submitKyc'])->name('eo.kyc.submit');
Route::get('/daftar-eo/pending', [EOController::class, 'pending'])->name('eo.pending');

Route::get('/eo/login', [EOController::class, 'login'])->name('eo.login');
Route::post('/eo/login', [EOController::class, 'authenticate'])->name('eo.login.authenticate');
Route::get('/eo/dashboard', [EOController::class, 'dashboard']) ->name('eo.dashboard');

Route::middleware([])->group(function () {
    Route::get('/eo/events', [EventController::class, 'index'])
        ->name('eo.events.index');

    Route::get('/eo/events/create', [EventController::class, 'create'])
        ->name('eo.events.create');

    Route::post('/eo/events', [EventController::class, 'store'])
        ->name('eo.events.store');

    Route::post('/eo/events/{id}/submit', [EventController::class, 'submitForReview'])
        ->name('eo.events.submit');
});

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
