<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DataKeluargaController;
use App\Http\Controllers\DataPekerjaanPendapatanController;
use App\Http\Controllers\DataRumahController;
use App\Http\Controllers\DataKendaraanController;
use App\Http\Controllers\SkorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExportController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::resource('survey', SurveyController::class);

// Data Keluarga
Route::resource('keluarga', DataKeluargaController::class)->except(['edit', 'update']);
Route::get('/keluarga/{keluarga}/edit', [DataKeluargaController::class, 'edit'])->name('keluarga.edit');
Route::put('/keluarga/{keluarga}', [DataKeluargaController::class, 'update'])->name('keluarga.update');

// Data Pekerjaan dan Pendapatan
Route::get('/pekerjaan', [DataPekerjaanPendapatanController::class, 'index'])->name('pekerjaan.index');
Route::get('/pekerjaan/create', [DataPekerjaanPendapatanController::class, 'create'])->name('pekerjaan.create');
Route::post('/pekerjaan', [DataPekerjaanPendapatanController::class, 'store'])->name('pekerjaan.store');

// Data Rumah
Route::resource('rumah', DataRumahController::class)->except(['edit', 'update']);
Route::get('/rumah/{rumah}/edit', [DataRumahController::class, 'edit'])->name('rumah.edit');
Route::put('/rumah/{rumah}', [DataRumahController::class, 'update'])->name('rumah.update');

// Data Kendaraan
Route::resource('kendaraan', DataKendaraanController::class)->except(['edit', 'update']);
Route::get('/kendaraan/{kendaraan}/edit', [DataKendaraanController::class, 'edit'])->name('kendaraan.edit');
Route::put('/kendaraan/{kendaraan}', [DataKendaraanController::class, 'update'])->name('kendaraan.update');

// Skor
Route::get('/skor', [SkorController::class, 'index'])->name('skor.index');
Route::post('/skor', [SkorController::class, 'store'])->name('skor.store');

// Profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// Export
Route::get('/export', [ExportController::class, 'index'])->name('export.index');
Route::get('/export/{survey}', [ExportController::class, 'exportPdf'])->name('export.pdf');
