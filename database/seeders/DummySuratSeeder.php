<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Domisili;
use App\Models\TidakMampu;
use App\Models\SuratUsaha;
use App\Models\Surat;
use Carbon\Carbon;

class DummySuratSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Data Surat Keterangan Domisili
        $domisiliData = [
            [
                'nama' => 'Ahmad Suherman',
                'nik' => '3313012505890001',
                'tempat_lahir' => 'Karanganyar',
                'tanggal_lahir' => '1989-05-25',
                'jenis_kelamin' => 'L',
                'pekerjaan' => 'Petani',
                'alamat' => 'Desa Karanganyar RT 02 RW 01',
                'keperluan' => 'Untuk mengurus KTP baru di Disdukcapil',
                'status' => 'selesai',
                'user_id' => null,
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'nama' => 'Eko Prasetyo',
                'nik' => '3313012206850002',
                'tempat_lahir' => 'Karanganyar',
                'tanggal_lahir' => '1985-06-22',
                'jenis_kelamin' => 'L',
                'pekerjaan' => 'Wiraswasta',
                'alamat' => 'Desa Karanganyar RT 03 RW 02',
                'keperluan' => 'Syarat pendaftaran sekolah anak',
                'status' => 'selesai',
                'user_id' => null,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'nama' => 'Hendra Gunawan',
                'nik' => '3313011503820003',
                'tempat_lahir' => 'Karanganyar',
                'tanggal_lahir' => '1982-03-15',
                'jenis_kelamin' => 'L',
                'pekerjaan' => 'Buruh',
                'alamat' => 'Desa Karanganyar RT 01 RW 01',
                'keperluan' => 'Persyaratan melamar pekerjaan',
                'status' => 'selesai',
                'user_id' => null,
                'created_at' => Carbon::now()->subMonth(1),
            ],
        ];

        foreach ($domisiliData as $data) {
            Domisili::create($data);
        }

        // Data Surat Keterangan Tidak Mampu
        $tidakMampuData = [
            [
                'nama' => 'Siti Aminah',
                'nik' => '3313016708920004',
                'tempat_lahir' => 'Karanganyar',
                'tanggal_lahir' => '1992-08-27',
                'jenis_kelamin' => 'Perempuan',
                'agama' => 'Islam',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'penghasilan' => 'Tidak Ada',
                'alamat' => 'Desa Karanganyar RT 04 RW 02',
                'jumlah_tanggungan' => 2,
                'status_rumah' => 'Kontrak',
                'keterangan_ekonomi' => 'Keluarga kurang mampu, suami buruh tani dengan penghasilan tidak tetap',
                'keperluan' => 'Mengurus beasiswa pendidikan anak',
                'status' => 'selesai',
                'user_id' => 1,
                'created_at' => Carbon::now()->subDays(8),
            ],
            [
                'nama' => 'Fatimah Zahra',
                'nik' => '3313017509880005',
                'tempat_lahir' => 'Karanganyar',
                'tanggal_lahir' => '1988-09-25',
                'jenis_kelamin' => 'Perempuan',
                'agama' => 'Islam',
                'pekerjaan' => 'Buruh Tani',
                'penghasilan' => 'Rp 500.000',
                'alamat' => 'Desa Karanganyar RT 05 RW 03',
                'jumlah_tanggungan' => 3,
                'status_rumah' => 'Milik Sendiri',
                'keterangan_ekonomi' => 'Keluarga pra sejahtera dengan kondisi ekonomi sulit',
                'keperluan' => 'Bantuan sosial dari pemerintah daerah',
                'status' => 'diproses',
                'user_id' => 1,
                'created_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($tidakMampuData as $data) {
            TidakMampu::create($data);
        }

        // Data Surat Keterangan Usaha
        $suratUsahaData = [
            [
                'nama_lengkap' => 'Bambang Wijaya',
                'nik' => '3313011204800006',
                'tempat_lahir' => 'Karanganyar',
                'tanggal_lahir' => '1980-04-12',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => 'Pedagang',
                'alamat' => 'Desa Karanganyar RT 02 RW 01',
                'nama_usaha' => 'Warung Makan Sederhana',
                'jenis_usaha' => 'Kuliner',
                'alamat_usaha' => 'Jl. Desa Karanganyar No. 15',
                'tanggal_mulai_usaha' => '2020-01-01',
                'modal_usaha' => 'Rp 5.000.000',
                'deskripsi_usaha' => 'Usaha warung makan yang menyediakan makanan dan minuman sederhana untuk masyarakat sekitar',
                'keperluan' => 'Mengurus izin usaha warung makan',
                'status' => 'diproses',
                'user_id' => null,
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'nama_lengkap' => 'Galih Ramadhan',
                'nik' => '3313012909900007',
                'tempat_lahir' => 'Karanganyar',
                'tanggal_lahir' => '1990-09-29',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'pekerjaan' => 'Petani',
                'alamat' => 'Desa Karanganyar RT 06 RW 03',
                'nama_usaha' => 'Toko Kelontong Berkah',
                'jenis_usaha' => 'Perdagangan',
                'alamat_usaha' => 'Desa Karanganyar RT 06 RW 03',
                'tanggal_mulai_usaha' => '2023-06-01',
                'modal_usaha' => 'Rp 10.000.000',
                'deskripsi_usaha' => 'Toko kelontong yang menjual kebutuhan sehari-hari masyarakat desa',
                'keperluan' => 'Persyaratan kredit usaha mikro di bank',
                'status' => 'menunggu',
                'user_id' => null,
                'created_at' => Carbon::now(),
            ],
        ];

        foreach ($suratUsahaData as $data) {
            SuratUsaha::create($data);
        }

        // Data Surat Umum (tabel surat)
        $suratData = [
            [
                'nik' => '3313016209870008',
                'nama' => 'Dwi Kartika',
                'alamat' => 'Desa Karanganyar RT 07 RW 04',
                'jenis_surat' => 'Surat Pengantar',
                'status' => 'Menunggu Verifikasi',
                'catatan' => 'Pengantar untuk mengurus BPJS Kesehatan',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'nik' => '3313011807920009',
                'nama' => 'Rudi Hartanto',
                'alamat' => 'Desa Karanganyar RT 08 RW 04',
                'jenis_surat' => 'Surat Keterangan Kelahiran',
                'status' => 'Menunggu Verifikasi',
                'catatan' => 'Keterangan kelahiran untuk anak yang baru lahir',
                'created_at' => Carbon::now()->subDay(),
            ],
        ];

        foreach ($suratData as $data) {
            Surat::create($data);
        }
    }
}
