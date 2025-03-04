<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PraktikumController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route home
Route::get('/', function () {
    return view('pages.welcome');
})->name('home');

// Route asisten
Route::get('/asisten', [AsistenController::class, 'getAsistens'])->name('asisten.index');

// Route profil digikom
Route::get('/digikom', function () {
    return view('pages.profil.index');
})->name('digikom.index');

// Route peminjaman
Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman');
Route::get('/peminjaman/start', [PeminjamanController::class, 'startPinjam'])->name('peminjaman.start');
Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayatPeminjaman'])->name('peminjaman.riwayat');
Route::get('/peminjaman/form', [PeminjamanController::class, 'formPinjam'])->name('peminjaman.form');

// Route praktikum
Route::get('/praktikum', [PraktikumController::class, 'getPraktikums'])->name('praktikum.index');
Route::get('/praktikum/{slug}', [ModulController::class, 'getModulsByPraktikum'])->name('moduls.praktikum');
Route::get('/moduls/download/{id}', [ModulController::class, 'downloadModul'])->name('moduls.download');

// Route untuk membuat data peminjaman
Route::get('/peminjaman/create', [PeminjamanController::class, 'createPeminjaman'])->name('peminjaman.create');
Route::post('/peminjaman', [PeminjamanController::class, 'storePeminjaman'])->name('peminjaman.store');

// Route artikel
Route::get('/artikel', [ArtikelController::class, 'getArtikels'])->name('artikel.index');
Route::get('/artikel/{id}', [ArtikelController::class, 'showArtikels'])->name('artikel.show');

// Route profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
