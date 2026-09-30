<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViewerController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/monitoring');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('monitoring')->name('viewer.')->controller(ViewerController::class)->group(function () {
    Route::get('/', 'dashboard')->name('dashboard');
    Route::get('/jadwal', 'jadwal')->name('jadwal.index');
    Route::get('/ruangan', 'ruangan')->name('ruangan.index');
    Route::get('/ruangan/{ruangan}', 'ruanganShow')->name('ruangan.show');
    Route::get('/perubahan', 'perubahan')->name('perubahan.index');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('jadwal', Admin\JadwalController::class)->except('show');

    $crud = ['index', 'store', 'update', 'destroy'];
    Route::resource('kelas', Admin\KelasController::class)->only($crud)->parameters(['kelas' => 'kelas']);
    Route::resource('mapel', Admin\MapelController::class)->only($crud)->parameters(['mapel' => 'mapel']);
    Route::resource('guru', Admin\GuruController::class)->only($crud)->parameters(['guru' => 'guru']);
    Route::resource('ruangan', Admin\RuanganController::class)->only($crud);
    Route::resource('guru-piket', Admin\GuruPiketController::class)->only($crud)->parameters(['guru-piket' => 'guruPiket']);
    Route::resource('perubahan', Admin\PerubahanController::class)->only($crud);
});