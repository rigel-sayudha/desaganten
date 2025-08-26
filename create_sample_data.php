<?php

// Create sample data for verification testing
use App\Models\Domisili;
use App\Models\TidakMampu;
use App\Models\BelumMenikah;

// Create sample Domisili
$domisili = new Domisili();
$domisili->nama = 'John Doe';
$domisili->nik = '1234567890123456';
$domisili->alamat = 'Jl. Test No. 123';
$domisili->tempat_lahir = 'Jakarta';
$domisili->tanggal_lahir = '1990-01-01';
$domisili->jenis_kelamin = 'L';
$domisili->agama = 'Islam';
$domisili->pekerjaan = 'Karyawan';
$domisili->keperluan = 'Untuk keperluan test verifikasi';
$domisili->user_id = 1;
$domisili->status = 'pending';
$domisili->save();

// Create sample Tidak Mampu
$tidakMampu = new TidakMampu();
$tidakMampu->nama = 'Jane Smith';
$tidakMampu->nik = '9876543210987654';
$tidakMampu->alamat = 'Jl. Sample No. 456';
$tidakMampu->tempat_lahir = 'Bandung';
$tidakMampu->tanggal_lahir = '1985-05-15';
$tidakMampu->jenis_kelamin = 'Perempuan';
$tidakMampu->agama = 'Kristen';
$tidakMampu->pekerjaan = 'Tidak Bekerja';
$tidakMampu->penghasilan = 'Tidak ada';
$tidakMampu->jumlah_tanggungan = 3;
$tidakMampu->status_rumah = 'Kontrak';
$tidakMampu->keterangan_ekonomi = 'Keluarga tidak mampu';
$tidakMampu->keperluan = 'Untuk bantuan sosial';
$tidakMampu->user_id = 1;
$tidakMampu->status = 'pending';
$tidakMampu->save();

// Create sample Belum Menikah
$belumMenikah = new BelumMenikah();
$belumMenikah->nama = 'Bob Wilson';
$belumMenikah->nik = '5555666677778888';
$belumMenikah->alamat = 'Jl. Demo No. 789';
$belumMenikah->tempat_lahir = 'Surabaya';
$belumMenikah->tanggal_lahir = '1995-12-20';
$belumMenikah->jenis_kelamin = 'Laki-laki';
$belumMenikah->agama = 'Islam';
$belumMenikah->pekerjaan = 'Mahasiswa';
$belumMenikah->nama_orang_tua = 'Robert Wilson Sr.';
$belumMenikah->pekerjaan_orang_tua = 'PNS';
$belumMenikah->alamat_orang_tua = 'Jl. Demo No. 789';
$belumMenikah->keperluan = 'Untuk melamar pekerjaan';
$belumMenikah->user_id = 1;
$belumMenikah->status = 'pending';
$belumMenikah->save();

echo "Sample data created successfully!\n";
echo "Domisili: {$domisili->nama}\n";
echo "Tidak Mampu: {$tidakMampu->nama}\n";
echo "Belum Menikah: {$belumMenikah->nama}\n";
