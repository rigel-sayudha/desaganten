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
            // Add user_id if it doesn't exist
            if (!Schema::hasColumn('surat_ktp', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            }
            
            // Add file_uploads if it doesn't exist
            if (!Schema::hasColumn('surat_ktp', 'file_uploads')) {
                $table->json('file_uploads')->nullable()->after('keperluan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_ktp', function (Blueprint $table) {
            if (Schema::hasColumn('surat_ktp', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            if (Schema::hasColumn('surat_ktp', 'file_uploads')) {
                $table->dropColumn('file_uploads');
            }
        });
    }
};
