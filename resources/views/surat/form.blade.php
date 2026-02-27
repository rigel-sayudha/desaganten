
@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto mt-12 bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-8 text-[#0088cc] text-center">Pilih Jenis Surat Pengantar / Keterangan</h2>
    @php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'Formulir', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <a href="{{ route('surat.ktp.form') }}" class="block bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-id-card"></i></div>
            <div class="font-bold mb-1">Surat Pengantar KTP Elektronik</div>
            <div class="text-sm text-gray-600">Permohonan pembuatan e-KTP baru atau penggantian</div>
        </a>
        <a href="{{ route('surat.kk.form') }}" class="block bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-users"></i></div>
            <div class="font-bold mb-1">Surat Pengantar KK</div>
            <div class="text-sm text-gray-600">Permohonan pembuatan Kartu Keluarga baru atau perubahan</div>
        </a>
        <a href="{{ route('surat.skck.form') }}" class="block bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-shield-alt"></i></div>
            <div class="font-bold mb-1">Surat Pengantar SKCK</div>
            <div class="text-sm text-gray-600">Permohonan pengantar pembuatan SKCK di kepolisian</div>
        </a>
        <a href="{{ route('surat.kehilangan.form') }}" class="block bg-pink-50 hover:bg-pink-100 border border-pink-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-search-minus"></i></div>
            <div class="font-bold mb-1">Surat Keterangan Kehilangan</div>
            <div class="text-sm text-gray-600">Permohonan surat keterangan kehilangan barang atau dokumen</div>
        </a>
        <a href="{{ route('surat.domisili.form') }}" class="block bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-home"></i></div>
            <div class="font-bold mb-1">Surat Keterangan Domisili</div>
            <div class="text-sm text-gray-600">Keterangan domisili untuk keperluan administrasi</div>
        </a>
        <a href="{{ route('surat.kematian.form') }}" class="block bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-cross"></i></div>
            <div class="font-bold mb-1">Surat Keterangan Kematian</div>
            <div class="text-sm text-gray-600">Keterangan kematian untuk keperluan administrasi</div>
        </a>
        <a href="{{ route('surat.kelahiran.form') }}" class="block bg-cyan-50 hover:bg-cyan-100 border border-cyan-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-baby"></i></div>
            <div class="font-bold mb-1">Surat Keterangan Kelahiran</div>
            <div class="text-sm text-gray-600">Keterangan kelahiran untuk keperluan administrasi</div>
        </a>
        <a href="{{ route('surat.usaha.form') }}" class="block bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-briefcase"></i></div>
            <div class="font-bold mb-1">Surat Keterangan Usaha</div>
            <div class="text-sm text-gray-600">Keterangan usaha untuk keperluan perizinan atau administrasi</div>
        </a>
        <a href="{{ route('surat.belum_menikah.form') }}" class="block bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-heart"></i></div>
            <div class="font-bold mb-1">Surat Keterangan Belum Menikah</div>
            <div class="text-sm text-gray-600">Keterangan status belum menikah untuk keperluan administrasi</div>
        </a>
        <a href="{{ route('surat.tidak_mampu.form') }}" class="block bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-6 shadow text-center transition">
            <div class="text-4xl mb-2"><i class="fas fa-hand-holding-heart"></i></div>
            <div class="font-bold mb-1">Surat Keterangan Tidak Mampu</div>
            <div class="text-sm text-gray-600">Keterangan ekonomi tidak mampu untuk keperluan bantuan sosial</div>
        </a>
    </div>
</div>
@endsection
