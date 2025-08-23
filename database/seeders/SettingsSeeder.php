<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'kepala_desa_nama',
            'value' => 'Munadi',
            'description' => 'Nama Kepala Desa untuk ditampilkan di surat keterangan'
        ]);

        Setting::create([
            'key' => 'kepala_desa_jabatan',
            'value' => 'Kepala Desa Ganten',
            'description' => 'Jabatan Kepala Desa untuk ditampilkan di surat keterangan'
        ]);
    }
}
