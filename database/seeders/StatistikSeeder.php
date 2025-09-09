<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StatistikPekerjaan;
use App\Models\StatistikUmur;
use App\Models\StatistikPendidikan;

class StatistikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Statistik Pekerjaan
        $pekerjaanData = [
            ['nama_pekerjaan' => 'Petani/Pekebun', 'laki_laki' => 280, 'perempuan' => 170],
            ['nama_pekerjaan' => 'Karyawan Swasta', 'laki_laki' => 220, 'perempuan' => 180],
            ['nama_pekerjaan' => 'Wiraswasta', 'laki_laki' => 200, 'perempuan' => 150],
            ['nama_pekerjaan' => 'Mengurus Rumah Tangga', 'laki_laki' => 5, 'perempuan' => 295],
            ['nama_pekerjaan' => 'Pelajar/Mahasiswa', 'laki_laki' => 125, 'perempuan' => 125],
            ['nama_pekerjaan' => 'Belum/Tidak Bekerja', 'laki_laki' => 80, 'perempuan' => 120],
            ['nama_pekerjaan' => 'Perdagangan', 'laki_laki' => 90, 'perempuan' => 110],
            ['nama_pekerjaan' => 'Pegawai Negeri Sipil', 'laki_laki' => 75, 'perempuan' => 75],
            ['nama_pekerjaan' => 'Industri', 'laki_laki' => 70, 'perempuan' => 50],
            ['nama_pekerjaan' => 'Peternak', 'laki_laki' => 65, 'perempuan' => 35],
            ['nama_pekerjaan' => 'Lainnya', 'laki_laki' => 50, 'perempuan' => 40],
            ['nama_pekerjaan' => 'Pensiunan', 'laki_laki' => 45, 'perempuan' => 35],
            ['nama_pekerjaan' => 'Konstruksi', 'laki_laki' => 75, 'perempuan' => 5],
            ['nama_pekerjaan' => 'Transportasi', 'laki_laki' => 65, 'perempuan' => 5],
            ['nama_pekerjaan' => 'Nelayan/Perikanan', 'laki_laki' => 45, 'perempuan' => 5],
            ['nama_pekerjaan' => 'TNI/Polri', 'laki_laki' => 25, 'perempuan' => 5],
        ];

        foreach ($pekerjaanData as $pekerjaan) {
            StatistikPekerjaan::create([
                'nama_pekerjaan' => $pekerjaan['nama_pekerjaan'],
                'laki_laki' => $pekerjaan['laki_laki'],
                'perempuan' => $pekerjaan['perempuan'],
                'jumlah' => $pekerjaan['laki_laki'] + $pekerjaan['perempuan'],
                'is_active' => true
            ]);
        }

        // Seed Statistik Umur
        $umurData = [
            ['kelompok_umur' => '0-1 Tahun', 'usia_min' => 0, 'usia_max' => 1, 'laki_laki' => 25, 'perempuan' => 23],
            ['kelompok_umur' => '2-4 Tahun', 'usia_min' => 2, 'usia_max' => 4, 'laki_laki' => 35, 'perempuan' => 32],
            ['kelompok_umur' => '5-9 Tahun', 'usia_min' => 5, 'usia_max' => 9, 'laki_laki' => 55, 'perempuan' => 52],
            ['kelompok_umur' => '10-14 Tahun', 'usia_min' => 10, 'usia_max' => 14, 'laki_laki' => 68, 'perempuan' => 65],
            ['kelompok_umur' => '15-19 Tahun', 'usia_min' => 15, 'usia_max' => 19, 'laki_laki' => 75, 'perempuan' => 72],
            ['kelompok_umur' => '20-24 Tahun', 'usia_min' => 20, 'usia_max' => 24, 'laki_laki' => 85, 'perempuan' => 88],
            ['kelompok_umur' => '25-29 Tahun', 'usia_min' => 25, 'usia_max' => 29, 'laki_laki' => 95, 'perempuan' => 98],
            ['kelompok_umur' => '30-34 Tahun', 'usia_min' => 30, 'usia_max' => 34, 'laki_laki' => 105, 'perempuan' => 108],
            ['kelompok_umur' => '35-39 Tahun', 'usia_min' => 35, 'usia_max' => 39, 'laki_laki' => 98, 'perempuan' => 102],
            ['kelompok_umur' => '40-44 Tahun', 'usia_min' => 40, 'usia_max' => 44, 'laki_laki' => 88, 'perempuan' => 92],
            ['kelompok_umur' => '45-49 Tahun', 'usia_min' => 45, 'usia_max' => 49, 'laki_laki' => 78, 'perempuan' => 82],
            ['kelompok_umur' => '50-54 Tahun', 'usia_min' => 50, 'usia_max' => 54, 'laki_laki' => 68, 'perempuan' => 72],
            ['kelompok_umur' => '55-59 Tahun', 'usia_min' => 55, 'usia_max' => 59, 'laki_laki' => 58, 'perempuan' => 62],
            ['kelompok_umur' => '60-64 Tahun', 'usia_min' => 60, 'usia_max' => 64, 'laki_laki' => 48, 'perempuan' => 52],
            ['kelompok_umur' => '65-69 Tahun', 'usia_min' => 65, 'usia_max' => 69, 'laki_laki' => 38, 'perempuan' => 42],
            ['kelompok_umur' => '70-74 Tahun', 'usia_min' => 70, 'usia_max' => 74, 'laki_laki' => 28, 'perempuan' => 32],
            ['kelompok_umur' => '75-79 Tahun', 'usia_min' => 75, 'usia_max' => 79, 'laki_laki' => 18, 'perempuan' => 22],
            ['kelompok_umur' => '80+ Tahun', 'usia_min' => 80, 'usia_max' => null, 'laki_laki' => 12, 'perempuan' => 18],
        ];

        foreach ($umurData as $umur) {
            StatistikUmur::create([
                'kelompok_umur' => $umur['kelompok_umur'],
                'usia_min' => $umur['usia_min'],
                'usia_max' => $umur['usia_max'],
                'laki_laki' => $umur['laki_laki'],
                'perempuan' => $umur['perempuan'],
                'jumlah' => $umur['laki_laki'] + $umur['perempuan'],
                'is_active' => true
            ]);
        }

        // Seed Statistik Pendidikan
        $pendidikanData = [
            ['tingkat_pendidikan' => 'Tidak/Belum Sekolah', 'urutan' => 1, 'laki_laki' => 180, 'perempuan' => 190],
            ['tingkat_pendidikan' => 'Tidak Tamat SD/Sederajat', 'urutan' => 2, 'laki_laki' => 85, 'perempuan' => 95],
            ['tingkat_pendidikan' => 'Tamat SD/Sederajat', 'urutan' => 3, 'laki_laki' => 320, 'perempuan' => 340],
            ['tingkat_pendidikan' => 'Tamat SLTP/Sederajat', 'urutan' => 4, 'laki_laki' => 380, 'perempuan' => 370],
            ['tingkat_pendidikan' => 'Tamat SLTA/Sederajat', 'urutan' => 5, 'laki_laki' => 420, 'perempuan' => 430],
            ['tingkat_pendidikan' => 'Tamat D-1/Sederajat', 'urutan' => 6, 'laki_laki' => 25, 'perempuan' => 35],
            ['tingkat_pendidikan' => 'Tamat D-2/Sederajat', 'urutan' => 7, 'laki_laki' => 30, 'perempuan' => 40],
            ['tingkat_pendidikan' => 'Tamat D-3/Sederajat', 'urutan' => 8, 'laki_laki' => 45, 'perempuan' => 55],
            ['tingkat_pendidikan' => 'Tamat S-1/Sederajat', 'urutan' => 9, 'laki_laki' => 120, 'perempuan' => 130],
            ['tingkat_pendidikan' => 'Tamat S-2/Sederajat', 'urutan' => 10, 'laki_laki' => 15, 'perempuan' => 20],
        ];

        foreach ($pendidikanData as $pendidikan) {
            StatistikPendidikan::create([
                'tingkat_pendidikan' => $pendidikan['tingkat_pendidikan'],
                'urutan' => $pendidikan['urutan'],
                'laki_laki' => $pendidikan['laki_laki'],
                'perempuan' => $pendidikan['perempuan'],
                'jumlah' => $pendidikan['laki_laki'] + $pendidikan['perempuan'],
                'is_active' => true
            ]);
        }
    }
}
