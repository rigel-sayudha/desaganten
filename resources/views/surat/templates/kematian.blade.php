@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'Kematian', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
<div class="max-w-3xl mx-auto mt-12 bg-gradient-to-br from-red-50 to-white p-0 rounded-2xl shadow-2xl">
    <div class="flex flex-col items-center justify-center py-8">
        <div class="bg-red-700 rounded-full p-4 mb-4 shadow-lg">
            <i class="fas fa-user-slash fa-3x text-white"></i>
        </div>
        <h2 class="text-3xl font-extrabold mb-2 text-red-700 text-center tracking-tight">Form Surat Keterangan Kematian</h2>
        <p class="text-gray-600 mb-6 text-center">Silakan isi data kematian dengan lengkap dan benar.</p>
    </div>
    <form method="POST" action="{{ route('surat.kematian.submit') }}" class="px-6 pb-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block font-semibold mb-2 text-red-700">Nama</label>
                <input type="text" name="nama" class="border border-red-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-red-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-red-700">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="border border-red-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-red-200" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-red-700">Tempat/Tanggal Lahir</label>
                <input type="text" name="tempat_lahir" class="border border-red-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-red-200" required>
                <input type="date" name="tanggal_lahir" class="border border-red-300 rounded-lg px-3 py-2 w-full mt-2 focus:ring focus:ring-red-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-red-700">Kewarganegaraan</label>
                <input type="text" name="kewarganegaraan" class="border border-red-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-red-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-red-700">Agama</label>
                <input type="text" name="agama" class="border border-red-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-red-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-red-700">Status Perkawinan</label>
                <input type="text" name="status_perkawinan" class="border border-red-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-red-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-red-700">Pekerjaan</label>
                <input type="text" name="pekerjaan" class="border border-red-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-red-200" required>
            </div>
            <div class="md:col-span-2">
                <label class="block font-semibold mb-2 text-red-700">Alamat</label>
                <textarea name="alamat" class="border border-red-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-red-200" required></textarea>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-red-700">RT/RK</label>
                <input type="text" name="rt_rw" class="border border-red-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-red-200" required>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-red-700 mb-4 flex items-center"><i class="fas fa-cross mr-2"></i>Keterangan Meninggal Dunia</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-2">Hari/Tanggal</label>
                    <input type="text" name="hari" class="border border-red-300 rounded-lg px-3 py-2 w-full" required>
                    <input type="date" name="tanggal_meninggal" class="border border-red-300 rounded-lg px-3 py-2 w-full mt-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Tempat Kematian</label>
                    <input type="text" name="tempat_kematian" class="border border-red-300 rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Kecamatan</label>
                    <input type="text" name="kecamatan" class="border border-red-300 rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Kabupaten</label>
                    <input type="text" name="kabupaten" class="border border-red-300 rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Provinsi</label>
                    <input type="text" name="provinsi" class="border border-red-300 rounded-lg px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Sebab Kematian</label>
                    <input type="text" name="sebab_kematian" class="border border-red-300 rounded-lg px-3 py-2 w-full" required>
                </div>
            </div>
        </div>
        <div class="flex justify-center">
            <button type="submit" class="bg-red-700 text-white px-8 py-3 rounded-xl font-bold text-lg shadow hover:bg-red-800 transition">Kirim Permohonan</button>
        </div>
    </form>
</div>
@endsection
