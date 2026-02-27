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
        if (Schema::hasTable('statistik_pendidikans') && !Schema::hasTable('statistik_pendidikan')) {
            Schema::rename('statistik_pendidikans', 'statistik_pendidikan');
        }
        if (Schema::hasTable('statistik_pekerjaans') && !Schema::hasTable('statistik_pekerjaan')) {
            Schema::rename('statistik_pekerjaans', 'statistik_pekerjaan');
        }
        if (Schema::hasTable('statistik_umurs') && !Schema::hasTable('statistik_umur')) {
            Schema::rename('statistik_umurs', 'statistik_umur');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('statistik_pendidikan', 'statistik_pendidikans');
        Schema::rename('statistik_pekerjaan', 'statistik_pekerjaans');
        Schema::rename('statistik_umur', 'statistik_umurs');
    }
};
