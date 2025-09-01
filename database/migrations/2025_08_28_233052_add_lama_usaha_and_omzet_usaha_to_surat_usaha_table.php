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
            $table->string('lama_usaha')->nullable()->after('alamat_usaha');
            $table->string('omzet_usaha')->nullable()->after('modal_usaha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_usaha', function (Blueprint $table) {
            $table->dropColumn(['lama_usaha', 'omzet_usaha']);
        });
    }
};
