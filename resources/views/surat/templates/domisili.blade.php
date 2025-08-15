@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'Domisili', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#22c55e',
            });
        });
    </script>
@endif
<div class="mt-12 flex flex-col lg:flex-row gap-8">
    <div class="flex-1 bg-white p-4 md:p-6 rounded-lg shadow-lg max-w-lg mx-auto">
        <h2 class="text-2xl font-bold mb-4 text-green-600 text-center">Formulir Surat Keterangan Domisili</h2>
        <form action="{{ url('/surat/domisili/submit') }}" method="POST" class="space-y-4 text-left">
            @csrf
            <div>
                <label class="block font-semibold mb-1">NIK</label>
                <input type="text" name="nik" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Nama Lengkap</label>
                <input type="text" name="nama" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full border rounded px-3 py-2" required>
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Kewarganegaraan</label>
                    <select name="kewarganegaraan" class="w-full border rounded px-3 py-2" required>
                        <option value="">Pilih</option>
                        <option value="WNI">WNI</option>
                        <option value="WNA">WNA</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                <label class="block font-semibold mb-1">Agama</label>
                <select name="agama" class="w-full border rounded px-3 py-2" required>
                    <option value="">Pilih</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Status Perkawinan</label>
                    <select name="status" class="w-full border rounded px-3 py-2" required>
                        <option value="">Pilih</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block font-semibold mb-1">Pekerjaan</label>
                <input type="text" name="pekerjaan" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Alamat Domisili</label>
                <textarea name="alamat" class="w-full border rounded px-3 py-2" rows="2" required></textarea>
            </div>
            <div>
                <label class="block font-semibold mb-1">Keperluan</label>
                <input type="text" name="keperluan" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="text-center mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">Kirim Permohonan</button>
            </div>
        </form>
    </div>
</div>
@endsection
