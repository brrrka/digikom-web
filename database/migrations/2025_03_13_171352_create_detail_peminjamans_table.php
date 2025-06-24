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
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_peminjaman')->constrained('peminjamans')->onDelete('cascade');
            $table->foreignId('id_inventaris')->constrained('inventaris')->onDelete('cascade');
            $table->integer('kuantitas')->default(1);
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['id_inventaris', 'id_peminjaman'], 'idx_detail_inventaris_peminjaman');
            $table->index('id_peminjaman', 'idx_detail_peminjaman');
            $table->index('id_inventaris', 'idx_detail_inventaris');

            // Add unique constraint to prevent duplicate entries
            $table->unique(['id_peminjaman', 'id_inventaris'], 'unique_peminjaman_inventaris');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
