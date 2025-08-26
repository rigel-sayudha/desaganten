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
        Schema::create('belum_menikah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('nik', 16);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->string('pekerjaan');
            $table->text('alamat');
            $table->string('nama_orang_tua');
            $table->string('pekerjaan_orang_tua');
            $table->text('alamat_orang_tua');
            $table->text('keperluan');
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->text('keterangan_admin')->nullable();
            $table->timestamp('tanggal_diproses')->nullable();
            $table->string('file_surat')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['nik']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('belum_menikah');
    }
};
