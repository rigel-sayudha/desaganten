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
        Schema::table('surat_usaha', function (Blueprint $table) {
            $table->string('file_ktp')->nullable()->after('keperluan');
            $table->string('file_kk')->nullable()->after('file_ktp');
            $table->string('file_foto_usaha')->nullable()->after('file_kk');
            $table->string('file_izin_usaha')->nullable()->after('file_foto_usaha');
            $table->string('file_pengantar')->nullable()->after('file_izin_usaha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_usaha', function (Blueprint $table) {
            $table->dropColumn(['file_ktp', 'file_kk', 'file_foto_usaha', 'file_izin_usaha', 'file_pengantar']);
        });
    }
};
