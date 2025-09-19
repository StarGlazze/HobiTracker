<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HobiController;

Route::get('/', function () {
    return view('landing.welcome');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});



Route::get('/aktivitas', function () {
    return view('admin.aktivitas');
});

Route::get('/logs', function () {
    return view('admin.logs');
});

Route::get('/target', function () {
    return view('admin.target');
});

Route::get('/profile', function () {
    return view('admin.profile');
});

Route::get('/setting', function () {
    return view('admin.setting');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('hobi', HobiController::class);
