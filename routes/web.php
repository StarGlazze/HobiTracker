<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HobiController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\TargetHobiController;
use App\Http\Controllers\ProgresTargetController;

Route::get('/', function () {
    return view('landing.welcome');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/logs', function () {
    return view('admin.logs');
});


Route::get('/profile', function () {
    return view('admin.profile');
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
        Route::get('target-progres', [TargetHobiController::class, 'indexProgres'])->name('target.progres');
        
        // Progress routes
        Route::resource('progres', ProgresTargetController::class)->except(['store', 'update', 'destroy']);
        Route::post('progres/store', [ProgresTargetController::class, 'store'])->name('progres.store');
        Route::put('progres/{progresTarget}', [ProgresTargetController::class, 'update'])->name('progres.update');
        Route::delete('progres/{progresTarget}', [ProgresTargetController::class, 'destroy'])->name('progres.destroy');
    });
});