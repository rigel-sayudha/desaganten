<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StatistikPekerjaan;

class StatistikPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if statistik pekerjaan data already exists
        if (StatistikPekerjaan::count() > 0) {
            $this->command->info('Statistik Pekerjaan data already exists. Skipping...');
            return;
        }

        // Data dummy untuk statistik pekerjaan penduduk Desa Ganten
        $pekerjaanData = [
            [
                'nama_pekerjaan' => 'Petani/Pekebun',
                'laki_laki' => 280,
                'perempuan' => 170,
                'keterangan' => 'Petani dan pekebun',
                'is_active' => true,
            ],
            [
                'nama_pekerjaan' => 'Karyawan Swasta',
                'laki_laki' => 220,
                'perempuan' => 180,
                'keterangan' => 'Bekerja di perusahaan swasta',
                'is_active' => true,
            ],
            [
                'nama_pekerjaan' => 'Wiraswasta',
                'laki_laki' => 200,
                'perempuan' => 150,
                'keterangan' => 'Usaha mandiri/wirausaha',
                'is_active' => true,
            ],
            [
                'nama_pekerjaan' => 'Mengurus Rumah Tangga',
                'laki_laki' => 5,
                'perempuan' => 295,
                'keterangan' => 'Ibu rumah tangga',
                'is_active' => true,
            ],
            [
                'nama_pekerjaan' => 'Pelajar/Mahasiswa',
                'laki_laki' => 125,
                'perempuan' => 125,
                'keterangan' => 'Sedang menempuh pendidikan',
                'is_active' => true,
            ],
            [
                'nama_pekerjaan' => 'Belum/Tidak Bekerja',
                'laki_laki' => 80,
                'perempuan' => 120,
                'keterangan' => 'Pencari kerja atau tidak bekerja',
                'is_active' => true,
            ],
            [
                'nama_pekerjaan' => 'Pedagang',
                'laki_laki' => 90,
                'perempuan' => 110,
                'keterangan' => 'Perdagangan barang dan jasa',
                'is_active' => true,
            ],
            [
                'nama_pekerjaan' => 'Pegawai Negeri Sipil',
                'laki_laki' => 75,
                'perempuan' => 75,
                'keterangan' => 'ASN/PNS',
                'is_active' => true,
            ],
            [
                'nama_pekerjaan' => 'Buruh Harian Lepas',
                'laki_laki' => 100,
                'perempuan' => 50,
                'keterangan' => 'Pekerja harian',
                'is_active' => true,
            ],
            [
                'nama_pekerjaan' => 'Pensiunan',
                'laki_laki' => 45,
                'perempuan' => 35,
                'keterangan' => 'Sudah pensiun',
                'is_active' => true,
            ],
        ];

        foreach ($pekerjaanData as $data) {
            StatistikPekerjaan::create($data);
        }

        $this->command->info('Statistik Pekerjaan data seeded successfully!');
    }
}
