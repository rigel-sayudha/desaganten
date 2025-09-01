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
        // Update surat_kelahiran, surat_usaha, surat_skck status from 'selesai' to 'selesai diproses'
        // These tables use varchar(255) for status column, so direct update is possible
        
        $tables = [
            'surat_kelahiran' => 'Surat Keterangan Kelahiran',
            'surat_usaha' => 'Surat Keterangan Usaha', 
            'surat_skck' => 'Surat Pengantar SKCK'
        ];

        foreach ($tables as $table => $description) {
            if (Schema::hasTable($table)) {
                // Update existing 'selesai' records to 'selesai diproses' for PDF download capability
                $updated = DB::table($table)->where('status', 'selesai')->update(['status' => 'selesai diproses']);
                
                if ($updated > 0) {
                    echo "Updated {$updated} records in {$table} ({$description})\n";
                }
            }
        }

        // Also check other potential surat tables that might have 'selesai' status
        $additionalTables = [
            'surat_kematian' => 'Surat Keterangan Kematian',
            'surat_kk' => 'Surat Pengantar Kartu Keluarga',
            'surat_ktp' => 'Surat Pengantar KTP',
            'surat_kehilangan' => 'Surat Keterangan Kehilangan'
        ];

        foreach ($additionalTables as $table => $description) {
            if (Schema::hasTable($table)) {
                $updated = DB::table($table)->where('status', 'selesai')->update(['status' => 'selesai diproses']);
                
                if ($updated > 0) {
                    echo "Updated {$updated} records in {$table} ({$description})\n";
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'selesai diproses' back to 'selesai'
        $tables = [
            'surat_kelahiran',
            'surat_usaha', 
            'surat_skck',
            'surat_kematian',
            'surat_kk',
            'surat_ktp',
            'surat_kehilangan'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->where('status', 'selesai diproses')->update(['status' => 'selesai']);
            }
        }
    }
};
