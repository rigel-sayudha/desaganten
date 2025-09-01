@extends(@include('partials.breadcrumbs', ['items' => $breadcrumbs])
<div class="max-w-4xl mx-auto mt-12 bg-white p-8 rounded-lg shadow-lg text-center">
    <div class="mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full shadow-lg mb-4">
            <i class="fas fa-home text-white text-2xl"></i>
        </div>
        <h2 class="text-3xl font-bold mb-4 text-amber-700">Formulir Surat Keterangan Pindah</h2>
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
                <p class="font-medium text-gray-700 mb-2">🏠 Data Perpindahan</p>
                <p>• Alamat asal yang ditinggalkan</p>
                <p>• Alamat tujuan yang baru</p>
                <p>• Alasan kepindahan</p>
                <p>• Tanggal rencana pindah</p>
                <p>• Jumlah anggota keluarga yang pindah</p>
            </div>
            <div class="border border-gray-200 rounded p-3 bg-white">
                <p class="font-medium text-gray-700 mb-2">📎 Upload Dokumen Pendukung</p>
                <p>• Scan KTP seluruh anggota keluarga</p>
                <p>• Scan Kartu Keluarga</p>
                <p>• Surat pernyataan pindah</p>
                <p>• Surat keterangan dari RT/RW asal</p>
                <p>• Surat keterangan penerimaan dari tujuan</p>
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
            class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg hover:from-amber-600 hover:to-orange-700 transition-all duration-200 font-medium"
        >
            <i class="fas fa-list mr-2"></i>
            Lihat Layanan Lain
        </a>
    </div>
</div>uts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'Pindah', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
<div class="max-w-xl mx-auto mt-12 bg-white p-8 rounded-lg shadow-lg text-center">
    <h2 class="text-2xl font-bold mb-4 text-pink-600">Formulir Surat Pengantar Pindah Penduduk</h2>
    <p class="text-gray-600">Halaman ini masih dalam pengembangan. Silakan kembali nanti.</p>
</div>
@endsection
