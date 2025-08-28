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
        Schema::create('surat_usaha', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('status_perkawinan');
            $table->string('kewarganegaraan')->default('WNI');
            $table->string('pekerjaan');
            $table->text('alamat');
            $table->string('nama_usaha');
            $table->string('jenis_usaha');
            $table->text('alamat_usaha');
            $table->date('tanggal_mulai_usaha');
            $table->string('modal_usaha')->nullable();
            $table->text('deskripsi_usaha');
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
        Schema::dropIfExists('surat_usaha');
    }
};
