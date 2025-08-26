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
        // Add verification stages to domisili table
        Schema::table('domisili', function (Blueprint $table) {
            $table->json('tahapan_verifikasi')->nullable()->after('status');
            $table->text('catatan_verifikasi')->nullable()->after('tahapan_verifikasi');
            $table->timestamp('tanggal_verifikasi_terakhir')->nullable()->after('catatan_verifikasi');
        });

        // Add verification stages to tidak_mampu table
        Schema::table('tidak_mampu', function (Blueprint $table) {
            $table->json('tahapan_verifikasi')->nullable()->after('status');
            $table->text('catatan_verifikasi')->nullable()->after('tahapan_verifikasi');
            $table->timestamp('tanggal_verifikasi_terakhir')->nullable()->after('catatan_verifikasi');
        });

        // Add verification stages to belum_menikah table
        Schema::table('belum_menikah', function (Blueprint $table) {
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
        Schema::table('domisili', function (Blueprint $table) {
            $table->dropColumn(['tahapan_verifikasi', 'catatan_verifikasi', 'tanggal_verifikasi_terakhir']);
        });

        Schema::table('tidak_mampu', function (Blueprint $table) {
            $table->dropColumn(['tahapan_verifikasi', 'catatan_verifikasi', 'tanggal_verifikasi_terakhir']);
        });

        Schema::table('belum_menikah', function (Blueprint $table) {
            $table->dropColumn(['tahapan_verifikasi', 'catatan_verifikasi', 'tanggal_verifikasi_terakhir']);
        });
    }
};
