<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AsetDigitalController;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OpenSeaController;

// Halaman Utama / Welcome
Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// RUTE DI DALAM PROTEKSI AUTH (HARUS LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Rute Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Route Manajemen Kriteria
    Route::resource('kriteria', KriteriaController::class);

    // 2. Route Manajemen Aset Digital (Alternatif & Penilaian)
    Route::resource('aset-digital', AsetDigitalController::class);

    // 3. Route Perhitungan & Hasil Akhir TOPSIS
    Route::get('/topsis/hasil', [TopsisController::class, 'hitung'])->name('topsis.hasil');

});

// ==========================================
// RUTE AUTENTIKASI (LOGIN, REGISTER, PASSWORD)
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// ==========================================
// RUTE DI DALAM PROTEKSI AUTH (HARUS LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Rute Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // TAMBAHKAN RUTE INI: Menangani proses export laporan PDF
    Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.exportPdf');

    // 1. Route Manajemen Kriteria
    Route::resource('kriteria', KriteriaController::class);

    // 2. Route Manajemen Aset Digital (Alternatif & Penilaian)
    Route::resource('aset-digital', AsetDigitalController::class);

    // 3. Route Perhitungan & Hasil Akhir TOPSIS
    Route::get('/topsis/hasil', [TopsisController::class, 'hitung'])->name('topsis.hasil');

});

// Route untuk uji coba mengambil data NFT berdasarkan slug koleksinya
Route::get('/fetch-opensea/{slug}', [OpenSeaController::class, 'fetchOpenSeaData']);

// Route untuk fetch Steam Market data
Route::get('/fetch-steam/{appId}/{itemName}', [OpenSeaController::class, 'fetchSteamData']);

// Route untuk fetch Blur NFT Marketplace data
Route::get('/fetch-blur/{contractAddress}/{tokenId}', [OpenSeaController::class, 'fetchBlurData']);

// Route untuk fetch Magic Eden (Solana) NFT data
Route::get('/fetch-magiceden/{mint}', [OpenSeaController::class, 'fetchMagicEdenData']);

// Route untuk batch fetch dari multiple platforms
Route::post('/fetch-batch', [OpenSeaController::class, 'fetchBatch']);