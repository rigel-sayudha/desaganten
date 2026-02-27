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
        if (Schema::hasTable('domisili')) {
            Schema::table('domisili', function (Blueprint $table) {
                if (!Schema::hasColumn('domisili', 'tahapan_verifikasi')) {
                    $table->json('tahapan_verifikasi')->nullable()->after('status');
                }
                if (!Schema::hasColumn('domisili', 'catatan_verifikasi')) {
                    $table->text('catatan_verifikasi')->nullable()->after('tahapan_verifikasi');
                }
                if (!Schema::hasColumn('domisili', 'tanggal_verifikasi_terakhir')) {
                    $table->timestamp('tanggal_verifikasi_terakhir')->nullable()->after('catatan_verifikasi');
                }
            });
        }

        // Add verification stages to tidak_mampu table
        if (Schema::hasTable('tidak_mampu')) {
            Schema::table('tidak_mampu', function (Blueprint $table) {
                if (!Schema::hasColumn('tidak_mampu', 'tahapan_verifikasi')) {
                    $table->json('tahapan_verifikasi')->nullable()->after('status');
                }
                if (!Schema::hasColumn('tidak_mampu', 'catatan_verifikasi')) {
                    $table->text('catatan_verifikasi')->nullable()->after('tahapan_verifikasi');
                }
                if (!Schema::hasColumn('tidak_mampu', 'tanggal_verifikasi_terakhir')) {
                    $table->timestamp('tanggal_verifikasi_terakhir')->nullable()->after('catatan_verifikasi');
                }
            });
        }

        // Add verification stages to belum_menikah table
        if (Schema::hasTable('belum_menikah')) {
            Schema::table('belum_menikah', function (Blueprint $table) {
                if (!Schema::hasColumn('belum_menikah', 'tahapan_verifikasi')) {
                    $table->json('tahapan_verifikasi')->nullable()->after('status');
                }
                if (!Schema::hasColumn('belum_menikah', 'catatan_verifikasi')) {
                    $table->text('catatan_verifikasi')->nullable()->after('tahapan_verifikasi');
                }
                if (!Schema::hasColumn('belum_menikah', 'tanggal_verifikasi_terakhir')) {
                    $table->timestamp('tanggal_verifikasi_terakhir')->nullable()->after('catatan_verifikasi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('domisili')) {
            Schema::table('domisili', function (Blueprint $table) {
                $table->dropColumn(['tahapan_verifikasi', 'catatan_verifikasi', 'tanggal_verifikasi_terakhir']);
            });
        }

        if (Schema::hasTable('tidak_mampu')) {
            Schema::table('tidak_mampu', function (Blueprint $table) {
                $table->dropColumn(['tahapan_verifikasi', 'catatan_verifikasi', 'tanggal_verifikasi_terakhir']);
            });
        }

        if (Schema::hasTable('belum_menikah')) {
            Schema::table('belum_menikah', function (Blueprint $table) {
                $table->dropColumn(['tahapan_verifikasi', 'catatan_verifikasi', 'tanggal_verifikasi_terakhir']);
            });
        }
    }
};
