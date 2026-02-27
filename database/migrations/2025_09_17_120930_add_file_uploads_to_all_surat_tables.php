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
        // Add file upload columns to domisili table
        if (Schema::hasTable('domisili') && !Schema::hasColumn('domisili', 'file_ktp')) {
            Schema::table('domisili', function (Blueprint $table) {
                $table->string('file_ktp')->nullable();
                $table->string('file_kk')->nullable();
                $table->string('file_pengantar_rt')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }

        // Add file upload columns to surat_kk table if not exists
        if (Schema::hasTable('surat_kk') && !Schema::hasColumn('surat_kk', 'file_ktp')) {
            Schema::table('surat_kk', function (Blueprint $table) {
                $table->string('file_ktp')->nullable();
                $table->string('file_kk_lama')->nullable();
                $table->string('file_surat_pindah')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }

        // Add file upload columns to surat_skck table if not exists  
        if (Schema::hasTable('surat_skck') && !Schema::hasColumn('surat_skck', 'file_ktp')) {
            Schema::table('surat_skck', function (Blueprint $table) {
                $table->string('file_ktp')->nullable();
                $table->string('file_kk')->nullable();
                $table->string('file_pas_foto')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }

        // Add file upload columns to surat_kematian table if not exists
        if (Schema::hasTable('surat_kematian') && !Schema::hasColumn('surat_kematian', 'file_ktp_pelapor')) {
            Schema::table('surat_kematian', function (Blueprint $table) {
                $table->string('file_ktp_pelapor')->nullable();
                $table->string('file_kk')->nullable();
                $table->string('file_surat_dokter')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }

        // Add file upload columns to surat_kelahiran table if not exists
        if (Schema::hasTable('surat_kelahiran') && !Schema::hasColumn('surat_kelahiran', 'file_ktp_ayah')) {
            Schema::table('surat_kelahiran', function (Blueprint $table) {
                $table->string('file_ktp_ayah')->nullable();
                $table->string('file_ktp_ibu')->nullable();
                $table->string('file_kk')->nullable();
                $table->string('file_surat_nikah')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }

        // Add file upload columns to belum_menikah table if not exists
        if (Schema::hasTable('belum_menikah') && !Schema::hasColumn('belum_menikah', 'file_ktp')) {
            Schema::table('belum_menikah', function (Blueprint $table) {
                $table->string('file_ktp')->nullable();
                $table->string('file_kk')->nullable();
                $table->string('file_pas_foto')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }

        // Add file upload columns to tidak_mampu table if not exists
        if (Schema::hasTable('tidak_mampu') && !Schema::hasColumn('tidak_mampu', 'file_ktp')) {
            Schema::table('tidak_mampu', function (Blueprint $table) {
                $table->string('file_ktp')->nullable();
                $table->string('file_kk')->nullable();
                $table->string('file_slip_gaji')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }

        // Add file upload columns to surat_kehilangan table if not exists
        if (Schema::hasTable('surat_kehilangan') && !Schema::hasColumn('surat_kehilangan', 'file_ktp_pelapor')) {
            Schema::table('surat_kehilangan', function (Blueprint $table) {
                $table->string('file_ktp_pelapor')->nullable();
                $table->string('file_kk')->nullable();
                $table->string('file_laporan_polisi')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }

        // Add file upload columns to surat_ktp table if not exists
        if (Schema::hasTable('surat_ktp') && !Schema::hasColumn('surat_ktp', 'file_ktp')) {
            Schema::table('surat_ktp', function (Blueprint $table) {
                $table->string('file_ktp')->nullable();
                $table->string('file_kk')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }

        // Add file upload columns to surat_usaha table if not exists
        if (Schema::hasTable('surat_usaha') && !Schema::hasColumn('surat_usaha', 'file_ktp')) {
            Schema::table('surat_usaha', function (Blueprint $table) {
                $table->string('file_ktp')->nullable();
                $table->string('file_kk')->nullable();
                $table->string('file_foto_usaha')->nullable();
                $table->string('file_dokumen_tambahan')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop file upload columns from domisili table
        if (Schema::hasTable('domisili')) {
            Schema::table('domisili', function (Blueprint $table) {
                $columns = ['file_ktp', 'file_kk', 'file_pengantar_rt', 'file_dokumen_tambahan'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('domisili', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        // Drop file upload columns from other tables
        $tables = [
            'surat_kk' => ['file_ktp', 'file_kk_lama', 'file_surat_pindah', 'file_dokumen_tambahan'],
            'surat_skck' => ['file_ktp', 'file_kk', 'file_pas_foto', 'file_dokumen_tambahan'],
            'surat_kematian' => ['file_ktp_pelapor', 'file_kk', 'file_surat_dokter', 'file_dokumen_tambahan'],
            'surat_kelahiran' => ['file_ktp_ayah', 'file_ktp_ibu', 'file_kk', 'file_surat_nikah', 'file_dokumen_tambahan'],
            'belum_menikah' => ['file_ktp', 'file_kk', 'file_pas_foto', 'file_dokumen_tambahan'],
            'tidak_mampu' => ['file_ktp', 'file_kk', 'file_slip_gaji', 'file_dokumen_tambahan'],
            'surat_kehilangan' => ['file_ktp_pelapor', 'file_kk', 'file_laporan_polisi', 'file_dokumen_tambahan'],
            'surat_ktp' => ['file_ktp', 'file_kk', 'file_dokumen_tambahan'],
            'surat_usaha' => ['file_ktp', 'file_kk', 'file_foto_usaha', 'file_dokumen_tambahan']
        ];

        foreach ($tables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($columns, $tableName) {
                    foreach ($columns as $column) {
                        if (Schema::hasColumn($tableName, $column)) {
                            $table->dropColumn($column);
                        }
                    }
                });
            }
        }
    }
};
