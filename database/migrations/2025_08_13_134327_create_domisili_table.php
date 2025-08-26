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
        Schema::create('domisili', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('kewarganegaraan')->default('Indonesia');
            $table->string('agama')->nullable();
            $table->string('status')->default('pending');
            $table->string('pekerjaan')->nullable();
            $table->text('alamat');
            $table->text('keperluan');
            $table->string('status_pengajuan')->default('pending');
            $table->json('tahapan_verifikasi')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamp('tanggal_verifikasi_terakhir')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domisili');
    }
};
