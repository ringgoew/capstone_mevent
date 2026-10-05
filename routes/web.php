<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EOController;

Route::get('/', function () {
    return view('welcome');
});


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
