<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\KontenController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
})->name('home');

// Route::get('/', [KontenController::class, 'getKontens'])->name('home');

Route::get('/asisten', [AsistenController::class, 'getAsistens'])->name('asisten.index');

Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayatPeminjaman'])->name('peminjaman.riwayat');

Route::get('/praktikum', [ModulController::class, 'getPraktikums'])->name('praktikum.index');

Route::get('/praktikum/modul', [ModulController::class, 'getModuls'])->name('moduls.index');

Route::get('/moduls/download/{id}', [ModulController::class, 'downloadModul'])->name('moduls.download');


// Route untuk membuat data peminjaman
Route::get('/peminjaman/create', [PeminjamanController::class, 'createPeminjaman'])->name('peminjaman.create');
Route::post('/peminjaman', [PeminjamanController::class, 'storePeminjaman'])->name('peminjaman.store');

Route::get('/artikel', [ArtikelController::class, 'getArtikels'])->name('artikel.index');
Route::get('/artikel/{id}', [ArtikelController::class, 'showArtikels'])->name('artikel.show');

Route::get('/dashboard', function () {
    return view('admin');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayatPeminjaman'])->name('peminjaman.riwayat');

    // Route untuk membuat data peminjaman
    // Route::get('/peminjaman/create', [PeminjamanController::class, 'createPeminjaman'])->name('peminjaman.create');
    // Route::post('/peminjaman', [PeminjamanController::class, 'storePeminjaman'])->name('peminjaman.store');
});

require __DIR__ . '/auth.php';
