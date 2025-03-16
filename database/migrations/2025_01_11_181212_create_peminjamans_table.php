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
            $table->date('tanggal_pengembalian')->nullable();
            $table->text('alasan');
            $table->enum('jangka', ['pendek', 'panjang']);
            $table->string('bukti_path')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'dipinjam', 'jatuh tenggat', 'dikembalikan'])->default('diajukan');
            $table->timestamps();
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
