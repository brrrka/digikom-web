<?php
// routes/web.php - Perbaikan untuk peminjaman routes

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PraktikumController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{identifier}', [ArtikelController::class, 'show'])->name('artikel.show');

// Route publik (tidak memerlukan autentikasi)
Route::get('/', function () {
    return view('pages.welcome');
})->name('home');

Route::get('/asisten', [AsistenController::class, 'getAsistens'])->name('asisten.index');

Route::get('/digikom', function () {
    return view('pages.profil.index');
})->name('digikom.index');

Route::prefix('praktikum')->group(function () {
    Route::get('/', [PraktikumController::class, 'getPraktikums'])->name('praktikum.index');
    Route::get('/{slug}', [ModulController::class, 'getModulsByPraktikum'])->name('moduls.praktikum');
});

Route::get('/moduls/download/{id}', [ModulController::class, 'downloadModul'])->name('moduls.download');
// Route yang memerlukan autentikasi dan verifikasi
Route::middleware(['auth', 'verified'])->group(function () {
    // Route Peminjaman - PERBAIKAN: Urutan routes yang benar
    Route::prefix('peminjaman')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('peminjaman');
        Route::get('/start', [PeminjamanController::class, 'startPinjam'])->name('peminjaman.start');
        Route::get('/form', [PeminjamanController::class, 'formPinjam'])->name('peminjaman.form');
        Route::post('/quantity', [PeminjamanController::class, 'quantitySelection'])->name('peminjaman.quantity');
        Route::get('/quantity', [PeminjamanController::class, 'showQuantityForm'])->name('peminjaman.quantity.show');
        Route::post('/confirm', [PeminjamanController::class, 'confirmPeminjaman'])->name('peminjaman.confirm');
        Route::post('/store', [PeminjamanController::class, 'storePeminjaman'])->name('peminjaman.store');
        Route::get('/status', [PeminjamanController::class, 'riwayatPeminjaman'])->name('peminjaman.status');
        Route::get('/create', [PeminjamanController::class, 'createPeminjaman'])->name('peminjaman.create');
        Route::get('/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::get('/{id}/download', [PeminjamanController::class, 'download'])->name('peminjaman.download');

        // PERBAIKAN: Route untuk AJAX check availability
        Route::post('/check-availability', [PeminjamanController::class, 'checkAvailability'])->name('peminjaman.check-availability');
    });
});

// Route profil (hanya memerlukan autentikasi, tidak perlu verifikasi)
Route::middleware('auth')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Admin routes
require __DIR__ . '/admin.php';
