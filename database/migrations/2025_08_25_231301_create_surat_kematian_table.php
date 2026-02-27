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
        Schema::create('surat_kematian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_almarhum');
            $table->string('nik_almarhum', 16);
            $table->string('tempat_lahir_almarhum');
            $table->date('tanggal_lahir_almarhum');
            $table->date('tanggal_kematian');
            $table->string('tempat_kematian');
            $table->string('sebab_kematian');
            $table->string('nama_pelapor');
            $table->string('nik_pelapor', 16);
            $table->string('hubungan_pelapor');
            $table->text('alamat_pelapor');
            $table->string('keperluan');
            $table->string('status')->default('menunggu');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->json('tahapan_verifikasi')->nullable();
            $table->json('catatan_verifikasi_detail')->nullable();
            $table->timestamp('tanggal_verifikasi_terakhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_kematian');
    }
};
