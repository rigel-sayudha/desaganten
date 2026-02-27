@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'Janda / Duda', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
<div class="max-w-4xl mx-auto mt-12 bg-white p-8 rounded-lg shadow-lg text-center">
    <div class="mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-violet-600 rounded-full shadow-lg mb-4">
            <i class="fas fa-user-check text-white text-2xl"></i>
        </div>
        <h2 class="text-3xl font-bold mb-4 text-purple-700">Formulir Surat Keterangan Janda/Duda</h2>
        <p class="text-gray-600 mb-8">Halaman ini masih dalam pengembangan. Silakan kembali nanti.</p>
    </div>
    
    <!-- Preview Form Structure for Future Development -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-left">
        <h4 class="font-semibold text-gray-700 mb-4 flex items-center space-x-2 justify-center">
            <i class="fas fa-eye text-gray-500"></i>
            <span>Preview Struktur Form (Dalam Pengembangan)</span>
        </h4>
        <div class="space-y-4 text-sm text-gray-600">
            <div class="border border-gray-200 rounded p-3 bg-white">
                <p class="font-medium text-gray-700 mb-2">👤 Data Status Perkawinan</p>
                <p>• Status saat ini (janda/duda)</p>
                <p>• Data perkawinan sebelumnya</p>
                <p>• Tanggal berakhirnya perkawinan</p>
                <p>• Sebab berakhirnya (perceraian/kematian)</p>
                <p>• Jumlah anak dari perkawinan sebelumnya</p>
            </div>
            <div class="border border-gray-200 rounded p-3 bg-white">
                <p class="font-medium text-gray-700 mb-2">📎 Upload Dokumen Pendukung</p>
                <p>• Scan KTP yang bersangkutan</p>
                <p>• Scan Kartu Keluarga</p>
                <p>• Scan Akta Kelahiran</p>
                <p>• Akta cerai/kematian pasangan</p>
                <p>• Surat nikah sebelumnya</p>
                <p>• Format: PDF, JPG, PNG (Max 2MB per file)</p>
            </div>
            <div class="border border-gray-200 rounded p-3 bg-white">
                <p class="font-medium text-gray-700 mb-2">⚡ Fitur Upload yang Akan Tersedia</p>
                <p>• Upload multiple files dengan drag & drop</p>
                <p>• Preview dokumen sebelum submit</p>
                <p>• Validasi format dan ukuran otomatis</p>
                <p>• Kompres gambar jika diperlukan</p>
            </div>
        </div>
    </div>
    
    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
        <button 
            onclick="history.back()"
            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium"
        >
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </button>
        
        <a 
            href="{{ url('/surat/form') }}"
            class="px-6 py-3 bg-gradient-to-r from-purple-500 to-violet-600 text-white rounded-lg hover:from-purple-600 hover:to-violet-700 transition-all duration-200 font-medium"
        >
            <i class="fas fa-list mr-2"></i>
            Lihat Layanan Lain
        </a>
    </div>
</div>
@endsection
