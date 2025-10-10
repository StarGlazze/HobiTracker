<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HobiController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\TargetHobiController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('landing.welcome');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logs', [LogAktivitasController::class, 'index'])->name('admin.logs');
    Route::get('/logs/export', [LogAktivitasController::class, 'export'])->name('logs.export');
    Route::resource('log-aktivitas', LogAktivitasController::class)->parameters([
        'log-aktivitas' => 'logAktivitas'
    ]);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/setting', [WebSettingController::class, 'index'])->name('setting.index');
    Route::post('/setting/save-settings', [WebSettingController::class, 'saveSettings'])->name('setting.save');
    Route::post('/setting/add-category', [WebSettingController::class, 'addCategory'])->name('setting.add.category');
    Route::delete('/setting/remove-category/{categoryId}', [WebSettingController::class, 'removeCategory'])->name('setting.remove.category');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::resource('hobi', HobiController::class);
    Route::resource('aktivitas', AktivitasController::class)->parameters([
        'aktivitas' => 'aktivitas'
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Target routes
        Route::resource('target', TargetHobiController::class);
    });
});
