@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'KK', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
<div class="max-w-2xl mx-auto mt-12">
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-1 rounded-xl shadow-xl">
        <div class="bg-white rounded-xl p-8">
            <div class="flex items-center justify-center mb-6">
                <span class="text-4xl text-indigo-600 mr-2">
                    <i class="fas fa-users"></i>
                </span>
                <h2 class="text-2xl font-bold text-indigo-700">Form Surat Keterangan KK</h2>
            </div>
            <form method="POST" action="{{ route('surat.kk.submit') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700"><i class="fas fa-user mr-1"></i> Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="border-2 border-indigo-200 rounded px-3 py-2 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700"><i class="fas fa-venus-mars mr-1"></i> Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="border-2 border-indigo-200 rounded px-3 py-2 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700"><i class="fas fa-pray mr-1"></i> Agama</label>
                        <input type="text" name="agama" class="border-2 border-indigo-200 rounded px-3 py-2 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700"><i class="fas fa-heart mr-1"></i> Status Perkawinan</label>
                        <select name="status_perkawinan" class="border-2 border-indigo-200 rounded px-3 py-2 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Cerai">Cerai</option>
                            <option value="Mati">Mati</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700"><i class="fas fa-id-card mr-1"></i> No KTP/NIK</label>
                        <input type="text" name="nik" maxlength="16" pattern="\d{16}" class="border-2 border-indigo-200 rounded px-3 py-2 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required placeholder="16 digit angka">
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700"><i class="fas fa-birthday-cake mr-1"></i> Tempat/Tanggal Lahir</label>
                        <input type="text" name="tempat_lahir" class="border-2 border-indigo-200 rounded px-3 py-2 w-full mb-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required placeholder="Tempat Lahir">
                        <input type="date" name="tanggal_lahir" class="border-2 border-indigo-200 rounded px-3 py-2 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700"><i class="fas fa-briefcase mr-1"></i> Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="border-2 border-indigo-200 rounded px-3 py-2 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700"><i class="fas fa-map-marker-alt mr-1"></i> Alamat</label>
                        <textarea name="alamat" class="border-2 border-indigo-200 rounded px-3 py-2 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required></textarea>
                    </div>
                </div>
                <div>
                    <label class="block font-semibold mb-2 text-gray-700"><i class="fas fa-file-alt mr-1"></i> Keperluan</label>
                    <input type="text" name="keperluan" class="border-2 border-indigo-200 rounded px-3 py-2 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                </div>
                <div class="flex justify-center mt-6">
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-pink-500 text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:scale-105 hover:shadow-xl transition-all duration-200 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
