<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.welcome');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/hobi', function () {
    return view('admin.hobi');
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

Route::get('/register', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});