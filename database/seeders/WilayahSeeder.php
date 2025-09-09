<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wilayah;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if wilayah data already exists
        if (Wilayah::count() > 0) {
            $this->command->info('Wilayah data already exists. Skipping...');
            return;
        }

        // Data dummy untuk wilayah Desa Ganten berdasarkan RW/RT
        $wilayahData = [
            [
                'nama' => 'RW 01',
                'laki_laki' => 180,
                'perempuan' => 195,
                'jumlah' => 375,
            ],
            [
                'nama' => 'RW 02',
                'laki_laki' => 165,
                'perempuan' => 178,
                'jumlah' => 343,
            ],
            [
                'nama' => 'RW 03',
                'laki_laki' => 145,
                'perempuan' => 162,
                'jumlah' => 307,
            ],
            [
                'nama' => 'RW 04',
                'laki_laki' => 158,
                'perempuan' => 171,
                'jumlah' => 329,
            ],
            [
                'nama' => 'RW 05',
                'laki_laki' => 142,
                'perempuan' => 154,
                'jumlah' => 296,
            ],
            [
                'nama' => 'RW 06',
                'laki_laki' => 135,
                'perempuan' => 147,
                'jumlah' => 282,
            ],
            [
                'nama' => 'RW 07',
                'laki_laki' => 128,
                'perempuan' => 139,
                'jumlah' => 267,
            ],
            [
                'nama' => 'RW 08',
                'laki_laki' => 151,
                'perempuan' => 164,
                'jumlah' => 315,
            ],
        ];

        foreach ($wilayahData as $data) {
            Wilayah::create($data);
        }

        $this->command->info('Wilayah data seeded successfully!');
    }
}
