<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Domisili;
use App\Models\BelumMenikah;
use App\Models\TidakMampu;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test domisili data
        Domisili::create([
            'nik' => '1234567890123456',
            'nama' => 'John Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pekerjaan' => 'Programmer',
            'alamat' => 'Jl. Test No. 1',
            'keperluan' => 'Testing domisili',
            'status_pengajuan' => 'pending'
        ]);

        Domisili::create([
            'nik' => '1234567890123457',
            'nama' => 'Jane Smith',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1992-05-15',
            'jenis_kelamin' => 'P',
            'agama' => 'Kristen',
            'pekerjaan' => 'Teacher',
            'alamat' => 'Jl. Test No. 2',
            'keperluan' => 'Testing domisili 2',
            'status_pengajuan' => 'approved'
        ]);

        // Create test belum_menikah data
        BelumMenikah::create([
            'nik' => '1234567890123458',
            'nama' => 'Bob Wilson',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1995-03-20',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pekerjaan' => 'Engineer',
            'alamat' => 'Jl. Test No. 3',
            'keperluan' => 'Testing belum menikah',
            'status' => 'pending'
        ]);

        // Create test tidak_mampu data
        TidakMampu::create([
            'nik' => '1234567890123459',
            'nama' => 'Sarah Johnson',
            'tempat_lahir' => 'Medan',
            'tanggal_lahir' => '1988-12-10',
            'jenis_kelamin' => 'P',
            'agama' => 'Hindu',
            'pekerjaan' => 'Buruh',
            'penghasilan' => '1000000',
            'alamat' => 'Jl. Test No. 4',
            'keperluan' => 'Testing tidak mampu',
            'status' => 'pending'
        ]);
    }
}
