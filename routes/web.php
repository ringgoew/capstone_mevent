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
