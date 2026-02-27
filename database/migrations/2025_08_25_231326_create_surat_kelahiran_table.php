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
        Schema::create('surat_kelahiran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bayi');
            $table->enum('jenis_kelamin_bayi', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->time('waktu_lahir');
            $table->string('nama_ayah');
            $table->string('nik_ayah', 16);
            $table->string('nama_ibu');
            $table->string('nik_ibu', 16);
            $table->text('alamat_orangtua');
            $table->string('nama_pelapor');
            $table->string('nik_pelapor', 16);
            $table->string('hubungan_pelapor');
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
        Schema::dropIfExists('surat_kelahiran');
    }
};
