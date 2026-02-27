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
        Schema::rename('rekap_surat_keluars', 'rekap_surat_keluar');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('rekap_surat_keluar', 'rekap_surat_keluars');
    }
};
