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
        // Rename tabel statistik dengan nama yang benar
        Schema::rename('statistik_pendidikans', 'statistik_pendidikan');
        Schema::rename('statistik_pekerjaans', 'statistik_pekerjaan');
        Schema::rename('statistik_umurs', 'statistik_umur');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan nama tabel ke nama asli
        Schema::rename('statistik_pendidikan', 'statistik_pendidikans');
        Schema::rename('statistik_pekerjaan', 'statistik_pekerjaans');
        Schema::rename('statistik_umur', 'statistik_umurs');
    }
};
