@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Pelayanan Surat', 'url' => url('/surat/form')],
    ['label' => 'Surat Pengantar Akta', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])

<!-- Modern Development Notice -->
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-purple-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full shadow-lg mb-6">
                <i class="fas fa-tools text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Pengantar Pembuatan Akta</h1>
            <p class="text-gray-600 max-w-lg mx-auto">
                Formulir untuk mengajukan surat pengantar pembuatan akta kelahiran, kematian, dan lainnya
            </p>
        </div>

        <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-2xl shadow-xl border border-white border-opacity-50 overflow-hidden">
            
            <!-- Notice Content -->
            <div class="p-8 text-center">

                <div class="mb-8">
                    <div class="relative">
                        <div class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class="fas fa-hard-hat text-white text-4xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center shadow-md">
                            <i class="fas fa-exclamation text-white text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Notice Message -->
                <div class="space-y-4 mb-8">
                    <h3 class="text-2xl font-bold text-gray-900">Sedang Dalam Pengembangan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Fitur formulir surat pengantar pembuatan akta sedang dalam tahap pengembangan. 
                        Kami sedang mempersiapkan sistem yang lebih baik untuk melayani kebutuhan Anda.
                    </p>
                </div>

                <!-- Development Timeline -->
                <div class="bg-purple-50 rounded-lg p-6 mb-8 text-left">
                    <h4 class="font-semibold text-purple-900 mb-4 flex items-center space-x-2">
                        <i class="fas fa-calendar-alt text-purple-600"></i>
                        <span>Timeline Pengembangan</span>
                    </h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-purple-800 text-sm">Analisis kebutuhan - <strong>Selesai</strong></span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                            <span class="text-purple-800 text-sm">Pengembangan formulir - <strong>Dalam Proses</strong></span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                            <span class="text-purple-800 text-sm">Testing & implementasi - <strong>Segera</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Alternative Actions -->
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button 
                            onclick="history.back()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium flex items-center justify-center space-x-2"
                        >
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </button>
                        
                        <a 
                            href="{{ url('/surat/form') }}"
                            class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all duration-200 font-medium flex items-center justify-center space-x-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                        >
                            <i class="fas fa-list"></i>
                            <span>Lihat Layanan Lain</span>
                        </a>
                    </div>
                    
                    <!-- Preview Form Structure for Future Development -->
                    <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h4 class="font-semibold text-gray-700 mb-4 flex items-center space-x-2">
                            <i class="fas fa-eye text-gray-500"></i>
                            <span>Preview Struktur Form (Dalam Pengembangan)</span>
                        </h4>
                        <div class="space-y-4 text-sm text-gray-600">
                            <div class="border border-gray-200 rounded p-3 bg-white">
                                <p class="font-medium text-gray-700 mb-2">📝 Data Pemohon</p>
                                <p>• Nama lengkap, NIK, alamat</p>
                                <p>• Jenis akta yang diminta (kelahiran/kematian/perkawinan)</p>
                                <p>• Data orang yang bersangkutan</p>
                            </div>
                            <div class="border border-gray-200 rounded p-3 bg-white">
                                <p class="font-medium text-gray-700 mb-2">📎 Upload Dokumen Pendukung</p>
                                <p>• Scan KTP pemohon</p>
                                <p>• Scan Kartu Keluarga</p>
                                <p>• Dokumen pendukung sesuai jenis akta</p>
                                <p>• Format: PDF, JPG, PNG (Max 2MB per file)</p>
                            </div>
                            <div class="border border-gray-200 rounded p-3 bg-white">
                                <p class="font-medium text-gray-700 mb-2">⚡ Fitur Upload yang Akan Tersedia</p>
                                <p>• Drag & drop multiple files</p>
                                <p>• Preview dokumen sebelum submit</p>
                                <p>• Progress bar upload real-time</p>
                                <p>• Validasi format dan ukuran otomatis</p>
                                <p>• Kompres gambar otomatis jika terlalu besar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h4 class="font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                <i class="fas fa-info-circle text-blue-600"></i>
                <span>Butuh Bantuan?</span>
            </h4>
            <div class="text-blue-800 text-sm space-y-2">
                <p class="flex items-center space-x-2">
                    <i class="fas fa-phone text-blue-600 w-4"></i>
                    <span>Hubungi kantor desa di <strong>(0271) 123-4567</strong></span>
                </p>
                <p class="flex items-center space-x-2">
                    <i class="fas fa-clock text-blue-600 w-4"></i>
                    <span>Jam pelayanan: Senin - Jumat, 08:00 - 16:00</span>
                </p>
                <p class="flex items-center space-x-2">
                    <i class="fas fa-map-marker-alt text-blue-600 w-4"></i>
                    <span>Atau datang langsung ke kantor Desa Ganten</span>
                </p>
            </div>
        </div>

        <!-- Quick Access to Other Services -->
        <div class="mt-8">
            <h4 class="font-semibold text-gray-900 mb-4 text-center">Layanan Surat Lainnya</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="/surat/domisili" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200 group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                            <i class="fas fa-home text-green-600"></i>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900">Domisili</h5>
                            <p class="text-sm text-gray-600">Surat keterangan domisili</p>
                        </div>
                    </div>
                </a>
                
                <a href="/surat/ktp" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200 group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-id-card text-blue-600"></i>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900">KTP</h5>
                            <p class="text-sm text-gray-600">Surat keterangan KTP</p>
                        </div>
                    </div>
                </a>
                
                <a href="/surat/kehilangan" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200 group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-900">Kehilangan</h5>
                            <p class="text-sm text-gray-600">Surat keterangan kehilangan</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
