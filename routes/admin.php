<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PraktikumController;
use App\Http\Controllers\Admin\ModulController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ArtikelController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    // Protected admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Praktikum Management
        Route::resource('praktikums', PraktikumController::class);

        // Modul Management
        Route::resource('moduls', ModulController::class);
        Route::post('moduls/{modul}/upload', [ModulController::class, 'uploadFile'])->name('moduls.upload');
        Route::delete('moduls/file/{id}', [ModulController::class, 'deleteFile'])->name('moduls.file.delete');

        // Inventaris Management
        Route::resource('inventaris', InventarisController::class);
        Route::post('inventaris/{inventaris}/upload', [InventarisController::class, 'uploadImage'])->name('inventaris.upload');

        // PERBAIKAN: Tambahkan route untuk bulk operations dan recalculate
        Route::post('inventaris/bulk-status', [InventarisController::class, 'bulkUpdateStatus'])->name('inventaris.bulk-status');
        Route::post('inventaris/recalculate-all', [InventarisController::class, 'recalculateAll'])->name('inventaris.recalculate-all');
        Route::get('inventaris/{inventaris}/stock', [InventarisController::class, 'getStock'])->name('inventaris.stock');

        // Peminjaman Management
        Route::resource('peminjaman', PeminjamanController::class);
        Route::patch('peminjaman/{peminjaman}/status', [PeminjamanController::class, 'updateStatus'])->name('peminjaman.status');
        Route::get('peminjaman/{peminjaman}/export', [PeminjamanController::class, 'exportPdf'])->name('peminjaman.export');
        Route::post('peminjaman/check-overdue', [PeminjamanController::class, 'checkOverdue'])->name('peminjaman.check-overdue');

        // PERBAIKAN: Tambahkan route untuk mendapatkan stok inventaris real-time
        Route::get('peminjaman/inventaris/{inventaris}/available', [PeminjamanController::class, 'getAvailableQuantity'])->name('peminjaman.available-quantity');

        // User Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::post('users/{user}/verify-email', [UserController::class, 'verifyEmail'])->name('users.verify-email');
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');

        // Artikel Management
        Route::resource('artikel', ArtikelController::class);

        // Export routes
        Route::get('export/all-data', [DashboardController::class, 'exportAllData'])->name('export.all');
        Route::get('export/peminjaman', [PeminjamanController::class, 'exportAll'])->name('export.peminjaman');
    });
});
