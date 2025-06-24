<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_selesai');
            $table->datetime('tanggal_pengembalian')->nullable();
            $table->text('alasan');
            $table->enum('jangka', ['pendek', 'panjang'])->default('pendek');
            $table->string('bukti_path')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', [
                'diajukan',
                'disetujui',
                'ditolak',
                'dipinjam',
                'jatuh tenggat',
                'dikembalikan'
            ])->default('diajukan');
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['status', 'tanggal_selesai'], 'idx_peminjaman_status_tanggal');
            $table->index(['id_users', 'status'], 'idx_peminjaman_user_status');
            $table->index('tanggal_peminjaman', 'idx_peminjaman_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
