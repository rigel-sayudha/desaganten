@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'Kelahiran', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
<div class="max-w-3xl mx-auto mt-12 bg-gradient-to-br from-blue-50 to-cyan-100 p-0 rounded-2xl shadow-2xl">
    <div class="flex flex-col items-center justify-center py-8">
        <div class="bg-[#0088cc] rounded-full p-4 mb-4 shadow-lg">
            <i class="fas fa-baby fa-3x text-white"></i>
        </div>
        <h2 class="text-3xl font-extrabold mb-2 text-[#0088cc] text-center tracking-tight">Form Surat Keterangan Kelahiran</h2>
        <p class="text-gray-600 mb-6 text-center">Silakan isi data kelahiran anak dan orang tua dengan lengkap dan benar.</p>
    </div>
    <form method="POST" action="{{ route('surat.kelahiran.submit') }}" class="px-6 pb-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block font-semibold mb-2 text-[#0088cc]">Nama Lengkap Anak</label>
                <input type="text" name="nama_anak" class="border border-cyan-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-cyan-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-[#0088cc]">Anak ke</label>
                <input type="number" name="anak_ke" class="border border-cyan-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-cyan-200" required min="1">
            </div>
            <div>
                <label class="block font-semibold mb-2 text-[#0088cc]">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="border border-cyan-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-cyan-200" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-[#0088cc]">Dilahirkan di</label>
                <input type="text" name="tempat_lahir_anak" class="border border-cyan-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-cyan-200" required>
            </div>
            <div class="md:col-span-2">
                <label class="block font-semibold mb-2 text-[#0088cc]">Alamat Anak</label>
                <textarea name="alamat_anak" class="border border-cyan-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-cyan-200" required></textarea>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block font-semibold mb-2 text-[#0088cc]">Penolong Kelahiran</label>
                <select name="penolong_kelahiran" class="border border-cyan-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-cyan-200" required>
                    <option value="">-- Pilih Penolong --</option>
                    <option value="Bidan">Bidan</option>
                    <option value="Dokter">Dokter</option>
                    <option value="Dukun">Dukun</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-[#0088cc]">Alamat Bidan Penolong</label>
                <input type="text" name="alamat_bidan" class="border border-cyan-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-cyan-200" required>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-[#0088cc] mb-4 flex items-center"><i class="fas fa-female mr-2"></i>Data Ibu</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2">NIK Ibu</label>
                    <input type="text" name="ibu_nik" class="border border-cyan-300 rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Nama Ibu</label>
                    <input type="text" name="ibu_nama" class="border border-cyan-300 rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Tempat/Tanggal Lahir Ibu</label>
                    <input type="text" name="ibu_tempat_lahir" class="border border-cyan-300 rounded-lg px-3 py-2 w-full" required>
                    <input type="date" name="ibu_tanggal_lahir" class="border border-cyan-300 rounded-lg px-3 py-2 w-full mt-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Alamat Ibu</label>
                    <textarea name="ibu_alamat" class="border border-cyan-300 rounded-lg px-3 py-2 w-full" required></textarea>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-[#0088cc] mb-4 flex items-center"><i class="fas fa-male mr-2"></i>Data Ayah</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2">NIK Ayah</label>
                    <input type="text" name="ayah_nik" class="border border-cyan-300 rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Nama Ayah</label>
                    <input type="text" name="ayah_nama" class="border border-cyan-300 rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Tempat/Tanggal Lahir Ayah</label>
                    <input type="text" name="ayah_tempat_lahir" class="border border-cyan-300 rounded-lg px-3 py-2 w-full" required>
                    <input type="date" name="ayah_tanggal_lahir" class="border border-cyan-300 rounded-lg px-3 py-2 w-full mt-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Alamat Ayah</label>
                    <textarea name="ayah_alamat" class="border border-cyan-300 rounded-lg px-3 py-2 w-full" required></textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-center">
            <button type="submit" class="bg-[#0088cc] text-white px-8 py-3 rounded-xl font-bold text-lg shadow hover:bg-[#0073b1] transition">Kirim Permohonan</button>
        </div>
    </form>
</div>
@endsection
