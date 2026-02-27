<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update tidak_mampu table status enum
        DB::statement("ALTER TABLE tidak_mampu MODIFY COLUMN status ENUM('pending', 'diproses', 'selesai', 'approved', 'ditolak') DEFAULT 'pending'");
        
        // Update belum_menikah table status enum
        DB::statement("ALTER TABLE belum_menikah MODIFY COLUMN status ENUM('pending', 'diproses', 'selesai', 'approved', 'ditolak') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert tidak_mampu table status enum
        DB::statement("ALTER TABLE tidak_mampu MODIFY COLUMN status ENUM('pending', 'diproses', 'selesai', 'ditolak') DEFAULT 'pending'");
        
        // Revert belum_menikah table status enum
        DB::statement("ALTER TABLE belum_menikah MODIFY COLUMN status ENUM('pending', 'diproses', 'selesai', 'ditolak') DEFAULT 'pending'");
    }
};
