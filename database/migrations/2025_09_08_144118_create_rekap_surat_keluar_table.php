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
        if (Schema::hasTable('rekap_surat_keluar')) {
            return;
        }
        Schema::create('rekap_surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_surat');
            $table->string('nomor_surat', 100)->nullable();
            $table->string('nama_pemohon');
            $table->string('jenis_surat');
            $table->text('untuk_keperluan');
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->string('surat_type')->nullable();
            $table->unsignedBigInteger('surat_id')->nullable(); 
            $table->timestamps();
            
            $table->index(['tanggal_surat', 'status']);
            $table->index('nomor_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_surat_keluar');
    }
};
