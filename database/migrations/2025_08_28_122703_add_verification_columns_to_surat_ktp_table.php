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
        Schema::table('surat_ktp', function (Blueprint $table) {
            $table->json('tahapan_verifikasi')->nullable()->after('status');
            $table->text('catatan_verifikasi')->nullable()->after('tahapan_verifikasi');
            $table->timestamp('tanggal_verifikasi_terakhir')->nullable()->after('catatan_verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_ktp', function (Blueprint $table) {
            $table->dropColumn(['tahapan_verifikasi', 'catatan_verifikasi', 'tanggal_verifikasi_terakhir']);
        });
    }
};
