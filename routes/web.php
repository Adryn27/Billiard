<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

// Meja Admin
Route::resource('/admin/customer',PelangganController::class, ['as'=>'backend'])->middleware('auth');

// Reservasi List Admin
Route::resource('/admin/reservasilist',ReservasiController::class, ['as'=>'backend'])->middleware('auth');