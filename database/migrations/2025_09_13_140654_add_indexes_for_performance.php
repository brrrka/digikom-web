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
        Schema::table('artikels', function (Blueprint $table) {
            $table->index(['status', 'published_at']);
            $table->index('created_at');
        });

        Schema::table('inventaris', function (Blueprint $table) {
            $table->index('kuantitas');
            $table->index('created_at');
        });

        Schema::table('peminjamen', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });

        Schema::table('detail_peminjamen', function (Blueprint $table) {
            $table->index('peminjaman_id');
            $table->index('inventaris_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->dropIndex(['status', 'published_at']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropIndex(['kuantitas']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('peminjamen', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('detail_peminjamen', function (Blueprint $table) {
            $table->dropIndex(['peminjaman_id']);
            $table->dropIndex(['inventaris_id']);
        });
    }
};
