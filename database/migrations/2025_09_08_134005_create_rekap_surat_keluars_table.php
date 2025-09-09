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
        Schema::create('rekap_surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_surat');
            $table->string('nomor_surat', 100);
            $table->string('nama_pemohon');
            $table->string('jenis_surat');
            $table->text('untuk_keperluan');
            $table->enum('status', ['selesai_diproses', 'ditolak'])->default('selesai_diproses');
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
        Schema::dropIfExists('rekap_surat_keluars');
    }
};
