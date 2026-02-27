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
        // Update enum values to include 'selesai diproses' status for PDF download capability
        
        // Check and update tidak_mampu table
        if (Schema::hasTable('tidak_mampu')) {
            $columns = DB::select("SHOW COLUMNS FROM tidak_mampu LIKE 'status'");
            if (!empty($columns)) {
                $currentType = $columns[0]->Type;
                if (strpos($currentType, 'selesai diproses') === false) {
                    DB::statement("ALTER TABLE tidak_mampu MODIFY COLUMN status enum('pending','diproses','selesai','selesai diproses','approved','ditolak','sudah diverifikasi','menunggu') DEFAULT 'pending'");
                }
            }
            
            // Update existing 'selesai' records to 'selesai diproses'
            DB::table('tidak_mampu')->where('status', 'selesai')->update(['status' => 'selesai diproses']);
        }

        // Check and update belum_menikah table
        if (Schema::hasTable('belum_menikah')) {
            $columns = DB::select("SHOW COLUMNS FROM belum_menikah LIKE 'status'");
            if (!empty($columns)) {
                $currentType = $columns[0]->Type;
                if (strpos($currentType, 'selesai diproses') === false) {
                    DB::statement("ALTER TABLE belum_menikah MODIFY COLUMN status enum('pending','diproses','selesai','selesai diproses','approved','ditolak','sudah diverifikasi','menunggu') DEFAULT 'pending'");
                }
            }
            
            // Update existing 'selesai' records to 'selesai diproses'
            DB::table('belum_menikah')->where('status', 'selesai')->update(['status' => 'selesai diproses']);
        }

        // Check and update other surat tables that might have similar issues
        $suratTables = [
            'surat_ktp' => 'nama_lengkap',
            'surat_kk' => 'nama_pemohon', 
            'surat_skck' => 'nama_lengkap',
            'surat_kematian' => 'nama_almarhum',
            'surat_kelahiran' => 'nama_bayi',
            'surat_usaha' => 'nama_lengkap',
            'surat_kehilangan' => 'nama_pelapor'
        ];

        foreach ($suratTables as $table => $nameColumn) {
            if (Schema::hasTable($table)) {
                $columns = DB::select("SHOW COLUMNS FROM {$table} LIKE 'status'");
                if (!empty($columns)) {
                    $currentType = $columns[0]->Type;
                    
                    // If it's an enum and doesn't have 'selesai diproses'
                    if (strpos($currentType, 'enum') !== false && strpos($currentType, 'selesai diproses') === false) {
                        // Add comprehensive status options
                        DB::statement("ALTER TABLE {$table} MODIFY COLUMN status enum('menunggu','pending','diproses','selesai','selesai diproses','approved','ditolak','sudah diverifikasi') DEFAULT 'menunggu'");
                        
                        // Update any existing 'selesai' records
                        DB::table($table)->where('status', 'selesai')->update(['status' => 'selesai diproses']);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reversing enum changes can be complex and might cause data loss
        // This migration primarily adds enum values and updates data, 
        // reversal would require careful consideration of existing data
        
        // Update 'selesai diproses' back to 'selesai' before removing enum value
        if (Schema::hasTable('tidak_mampu')) {
            DB::table('tidak_mampu')->where('status', 'selesai diproses')->update(['status' => 'selesai']);
        }
        
        if (Schema::hasTable('belum_menikah')) {
            DB::table('belum_menikah')->where('status', 'selesai diproses')->update(['status' => 'selesai']);
        }
        
        // Revert enum changes (commented out to prevent data loss)
        // DB::statement("ALTER TABLE tidak_mampu MODIFY COLUMN status enum('pending','diproses','selesai','approved','ditolak') DEFAULT 'pending'");
        // DB::statement("ALTER TABLE belum_menikah MODIFY COLUMN status enum('pending','diproses','selesai','approved','ditolak') DEFAULT 'pending'");
    }
};
