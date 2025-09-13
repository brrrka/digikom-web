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
            // Skip status+published_at index - already exists in create_artikels_table
            // Skip created_at index - already exists in create_artikels_table
        });

        Schema::table('inventaris', function (Blueprint $table) {
            // Skip kuantitas index - already covered by composite index in create_inventaris_table
            // Skip created_at index - appears to already exist
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            // Skip id_users + status index - already exists as 'idx_peminjaman_user_status'
            // Skip created_at index - appears to already exist
        });

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            // Skip indexes - already exist as 'idx_detail_peminjaman' and 'idx_detail_inventaris'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            // No indexes to drop - they were created in create_artikels_table
        });

        Schema::table('inventaris', function (Blueprint $table) {
            // No indexes to drop - they were created elsewhere
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            // No indexes to drop - they were created elsewhere
        });

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            // No indexes to drop - they were created in create_detail_peminjamans_table
        });
    }
};
