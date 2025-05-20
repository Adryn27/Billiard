<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
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