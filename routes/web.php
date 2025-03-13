<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AsistenController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PraktikumController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Email Verification Handler
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend Verification Email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/', function () {
    return view('pages.welcome');
})->name('home');

Route::get('/asisten', [AsistenController::class, 'getAsistens'])->name('asisten.index');

Route::get('/digikom', function () {
    return view('pages.profil.index');
})->name('digikom.index');

// Route Peminjaman
Route::prefix('peminjaman')->group(function () {
    Route::get('/', [PeminjamanController::class, 'index'])->name('peminjaman');
    Route::get('/start', [PeminjamanController::class, 'startPinjam'])->name('peminjaman.start');
    Route::get('/status', [PeminjamanController::class, 'riwayatPeminjaman'])->name('peminjaman.status');
    Route::get('/form', [PeminjamanController::class, 'formPinjam'])->name('peminjaman.form');
    Route::get('/quantity', [PeminjamanController::class, 'showQuantityForm'])->name('peminjaman.quantity.show');
    Route::post('/quantity', [PeminjamanController::class, 'quantitySelection'])->name('peminjaman.quantity');
    Route::post('/confirm', [PeminjamanController::class, 'confirmPeminjaman'])->name('peminjaman.confirm');
    Route::post('/', [PeminjamanController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::get('/create', [PeminjamanController::class, 'createPeminjaman'])->name('peminjaman.create');
    Route::get('/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::get('/peminjaman/{id}/download', [PeminjamanController::class, 'download'])->name('peminjaman.download');
});

// Route Praktikum & Modul
Route::prefix('praktikum')->group(function () {
    Route::get('/', [PraktikumController::class, 'getPraktikums'])->name('praktikum.index');
    Route::get('/{slug}', [ModulController::class, 'getModulsByPraktikum'])->name('moduls.praktikum');
});

Route::get('/moduls/download/{id}', [ModulController::class, 'downloadModul'])->name('moduls.download');

// Route Artikel
Route::prefix('artikel')->group(function () {
    Route::get('/', [ArtikelController::class, 'getArtikels'])->name('artikel.index');
    Route::get('/{id}', [ArtikelController::class, 'showArtikels'])->name('artikel.show');
});

// Route Profil
Route::middleware('auth')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
