@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'SKCK', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
<div class="max-w-3xl mx-auto mt-12 bg-gradient-to-br from-yellow-50 to-white p-0 rounded-2xl shadow-2xl">
    <div class="flex flex-col items-center justify-center py-8">
        <div class="bg-yellow-500 rounded-full p-4 mb-4 shadow-lg">
            <i class="fas fa-id-card fa-3x text-white"></i>
        </div>
        <h2 class="text-3xl font-extrabold mb-2 text-yellow-700 text-center tracking-tight">Form Surat Pengantar SKCK</h2>
        <p class="text-gray-600 mb-6 text-center">Silakan isi data permohonan SKCK dengan lengkap dan benar.</p>
    </div>
    <form method="POST" action="{{ route('surat.skck.submit') }}" class="px-6 pb-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block font-semibold mb-2 text-yellow-700">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="border border-yellow-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-yellow-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-yellow-700">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="border border-yellow-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-yellow-200" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-yellow-700">Tempat/Tanggal Lahir</label>
                <input type="text" name="tempat_lahir" class="border border-yellow-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-yellow-200" required>
                <input type="date" name="tanggal_lahir" class="border border-yellow-300 rounded-lg px-3 py-2 w-full mt-2 focus:ring focus:ring-yellow-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-yellow-700">Status Perkawinan</label>
                <input type="text" name="status_perkawinan" class="border border-yellow-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-yellow-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-yellow-700">Kewarganegaraan</label>
                <select name="kewarganegaraan" class="border border-yellow-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-yellow-200" required>
                    <option value="">-- Pilih Kewarganegaraan --</option>
                    <option value="WNI">WNI</option>
                    <option value="WNA">WNA</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-yellow-700">Agama</label>
                <select name="agama" class="border border-yellow-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-yellow-200" required>
                    <option value="">-- Pilih Agama --</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-yellow-700">Pekerjaan</label>
                <input type="text" name="pekerjaan" class="border border-yellow-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-yellow-200" required>
            </div>
            <div>
                <label class="block font-semibold mb-2 text-yellow-700">Nomor Induk Kependudukan</label>
                <input type="text" name="nik" class="border border-yellow-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-yellow-200" required>
            </div>
            <div class="md:col-span-2">
                <label class="block font-semibold mb-2 text-yellow-700">Alamat</label>
                <textarea name="alamat" class="border border-yellow-300 rounded-lg px-3 py-2 w-full focus:ring focus:ring-yellow-200" required></textarea>
            </div>
        </div>
        <div class="flex justify-center">
            <button type="submit" class="bg-yellow-600 text-white px-8 py-3 rounded-xl font-bold text-lg shadow hover:bg-yellow-700 transition">Kirim Permohonan</button>
        </div>
    </form>
</div>
@endsection
