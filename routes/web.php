<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaitingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('beranda');
});

// Login Admin
Route::get('/admin/login',[LoginController::class,'loginBackend'])->name('backend.login');
Route::post('/admin/login',[LoginController::class,'authenticateBackend'])->name('backend.login');
Route::get('/admin/logout',[LoginController::class,'logoutBackend'])->name('backend.logout');

// Beranda Admin
Route::resource('/admin/dashboard',BerandaController::class, ['as'=>'backend'])->middleware('auth');

// User Admin
Route::resource('/admin/user',UserController::class, ['as'=>'backend'])->middleware('auth');
Route::get('/admin/profile', [UserController::class, 'profil'])->name('profile')->middleware('auth');

// Kategori Admin
Route::resource('/admin/category',KategoriController::class, ['as'=>'backend'])->middleware('auth');

// Meja Admin
Route::resource('/admin/table',MejaController::class, ['as'=>'backend'])->middleware('auth');

// Pelanggan Admin
Route::resource('/admin/customer',PelangganController::class, ['as'=>'backend'])->middleware('auth');

// Reservasi List Admin
Route::resource('/admin/reservasilist',ReservasiController::class, ['as'=>'backend'])->middleware('auth');

// Waiting List Admin
Route::resource('/admin/waitinglist',WaitingController::class, ['as'=>'backend'])->middleware('auth');

// Transaksi Admin
Route::get('/admin/transaction', [ReservasiController::class, 'transaksi'])->name('transaksi')->middleware('auth');
Route::post('/admin/transaction/report',[ReservasiController::class,'cetak'])->name('laporan.cetak')->middleware('auth');

// Frontend
Route::get('/', [BerandaController::class, 'frontendIndex'])->name('beranda');
Route::get('/reservation', [ReservasiController::class, 'reservasiCreate'])->name('reservasi');
Route::get('/history', [BerandaController::class, 'historyIndex'])->name('history');

// Reservasi
Route::post('/reservation/create', [ReservasiController::class, 'reservasiStore'])->name('reserved');

// Google
Route::get('auth/google', [PelangganController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [PelangganController::class, 'handleGoogleCallback']);

// Logout 
Route::post('/logout', [PelangganController::class, 'logout'])->name('logout');