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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('kuantitas')->default(0);
            $table->integer('total_dipinjam')->default(0);
            $table->string('images')->nullable();
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['status', 'kuantitas', 'total_dipinjam'], 'idx_inventaris_availability');
            $table->index('nama', 'idx_inventaris_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
