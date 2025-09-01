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
        Schema::table('belum_menikah', function (Blueprint $table) {
            $table->string('no_telepon', 20)->nullable()->after('pekerjaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('belum_menikah', function (Blueprint $table) {
            $table->dropColumn('no_telepon');
        });
    }
};
