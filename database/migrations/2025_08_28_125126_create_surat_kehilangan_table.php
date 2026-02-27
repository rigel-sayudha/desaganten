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
        Schema::create('surat_kehilangan', function (Blueprint $table) {
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
            $table->string('barang_hilang');
            $table->text('deskripsi_barang');
            $table->string('tempat_kehilangan');
            $table->date('tanggal_kehilangan');
            $table->time('waktu_kehilangan')->nullable();
            $table->text('kronologi_kehilangan');
            $table->string('no_laporan_polisi')->nullable();
            $table->date('tanggal_laporan_polisi')->nullable();
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
        Schema::dropIfExists('surat_kehilangan');
    }
};
