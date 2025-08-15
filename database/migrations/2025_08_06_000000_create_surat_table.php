<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('nik',16);
            $table->string('nama');
            $table->string('alamat');
            $table->string('jenis_surat');
            $table->string('file_berkas')->nullable();
            $table->string('status')->default('Menunggu Verifikasi');
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('surat');
    }
};
