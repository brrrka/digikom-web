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

        // PERBAIKAN: Inventaris routes - specific routes HARUS di atas resource routes
        Route::group(['prefix' => 'inventaris', 'as' => 'inventaris.'], function () {
            // Export routes - HARUS di atas yang lain
            Route::get('export', [InventarisController::class, 'export'])->name('export');
            Route::get('export-selected', [InventarisController::class, 'exportSelected'])->name('export-selected');
            Route::get('download-template', [InventarisController::class, 'downloadTemplate'])->name('download-template');

            // Import routes
            Route::get('import', [InventarisController::class, 'showImportForm'])->name('import-form');
            Route::post('import', [InventarisController::class, 'import'])->name('import');

            // AJAX/API routes
            Route::post('bulk-export', [InventarisController::class, 'bulkExport'])->name('bulk-export');
            Route::post('bulk-update-status', [InventarisController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
            Route::post('recalculate-all', [InventarisController::class, 'recalculateAll'])->name('recalculate-all');

            // Individual item routes dengan parameter
            Route::post('{inventaris}/upload-image', [InventarisController::class, 'uploadImage'])->name('upload-image');
            Route::get('{inventaris}/stock', [InventarisController::class, 'getStock'])->name('get-stock');
        });

        // Inventaris Resource routes - HARUS setelah specific routes
        Route::resource('inventaris', InventarisController::class);

        // Peminjaman Management
        Route::resource('peminjaman', PeminjamanController::class);
        Route::patch('peminjaman/{peminjaman}/status', [PeminjamanController::class, 'updateStatus'])->name('peminjaman.status');
        Route::get('peminjaman/{peminjaman}/export', [PeminjamanController::class, 'exportPdf'])->name('peminjaman.export');
        Route::post('peminjaman/check-overdue', [PeminjamanController::class, 'checkOverdue'])->name('peminjaman.check-overdue');
        Route::get('peminjaman/inventaris/{inventaris}/available', [PeminjamanController::class, 'getAvailableQuantity'])->name('peminjaman.available-quantity');

        // User Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::post('users/{user}/verify-email', [UserController::class, 'verifyEmail'])->name('users.verify-email');
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');

        // Artikel Management
        Route::resource('artikel', ArtikelController::class);
        Route::patch('artikel/{artikel}/toggle-status', [ArtikelController::class, 'toggleStatus'])->name('artikel.toggle-status');
        Route::post('artikel/{artikel}/duplicate', [ArtikelController::class, 'duplicate'])->name('artikel.duplicate');
        Route::post('artikel/bulk-action', [ArtikelController::class, 'bulkAction'])->name('artikel.bulk-action');
        Route::post('artikel/upload-image', [ArtikelController::class, 'uploadImage'])->name('artikel.upload-image');
        Route::get('artikel/{artikel}/preview', [ArtikelController::class, 'preview'])->name('artikel.preview');

        // Export routes - Global
        Route::get('export/all-data', [DashboardController::class, 'exportAllData'])->name('export.all');
        Route::get('export/peminjaman', [PeminjamanController::class, 'exportAll'])->name('export.peminjaman');
    });
});
