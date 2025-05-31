<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KebijakanController;
use App\Http\Controllers\TrsPinjamController;
use App\Http\Controllers\TrsKembaliController;
use App\Http\Controllers\ReportPinjamController;
use App\Http\Controllers\ReportKembaliController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::resource('anggota', AnggotaController::class);
    Route::resource('koleksi', KoleksiController::class);
    Route::resource('kebijakan', KebijakanController::class);
    Route::resource('trsPinjam', TrsPinjamController::class);
    Route::resource('trsKembali', TrsKembaliController::class);
    Route::resource('reportPinjam', ReportPinjamController::class);
    Route::resource('reportKembali', ReportKembaliController::class);
});

require __DIR__.'/auth.php';
