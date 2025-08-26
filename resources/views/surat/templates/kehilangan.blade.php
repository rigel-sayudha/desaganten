@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Pelayanan Surat', 'url' => url('/surat/form')],
    ['label' => 'Surat Keterangan Kehilangan', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])

<!-- Modern Form Container -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        
        <!-- Form Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-full shadow-lg mb-4">
                <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Keterangan Kehilangan</h1>
            <p class="text-gray-600 max-w-lg mx-auto">
                Silakan lengkapi formulir di bawah ini untuk mengajukan permohonan surat keterangan kehilangan
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-2xl shadow-xl border border-white border-opacity-50 overflow-hidden">
            
            <!-- Form Content -->
            <div class="p-8 space-y-8" x-data="{ 
                formData: {
                    nama: '',
                    tempat_tanggal_lahir: '',
                    alamat: '',
                    barang_hilang: '',
                    tempat_kehilangan: '',
                    tanggal_kehilangan: ''
                },
                isSubmitting: false
            }">
                
                <!-- Alert for Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span class="text-green-700 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                            <div class="flex-1">
                                <h4 class="text-red-800 font-medium text-sm mb-1">Terdapat kesalahan:</h4>
                                <ul class="text-red-700 text-sm list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('surat.kehilangan.submit') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-user text-[#0088cc]"></i>
                                <span>Data Pemohon</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi pribadi pemohon surat keterangan</p>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="space-y-2">
                            <label for="nama" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-user text-blue-500 w-4"></i>
                                <span>Nama Lengkap</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nama"
                                name="nama" 
                                x-model="formData.nama"
                                value="{{ old('nama') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Masukkan nama lengkap sesuai KTP"
                                required
                            >
                        </div>

                        <!-- Tempat, Tanggal Lahir -->
                        <div class="space-y-2">
                            <label for="tempat_tanggal_lahir" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-birthday-cake text-purple-500 w-4"></i>
                                <span>Tempat, Tanggal Lahir</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="tempat_tanggal_lahir"
                                name="tempat_tanggal_lahir" 
                                x-model="formData.tempat_tanggal_lahir"
                                value="{{ old('tempat_tanggal_lahir') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Contoh: Jakarta, 15 Agustus 1990"
                                required
                            >
                        </div>

                        <!-- Alamat -->
                        <div class="space-y-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-map-marker-alt text-green-500 w-4"></i>
                                <span>Alamat Lengkap</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="alamat"
                                name="alamat" 
                                x-model="formData.alamat"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Masukkan alamat lengkap sesuai KTP"
                                required
                            >{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <!-- Loss Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-search text-red-500"></i>
                                <span>Informasi Kehilangan</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Detail mengenai barang yang hilang</p>
                        </div>

                        <!-- Barang yang Hilang -->
                        <div class="space-y-2">
                            <label for="barang_hilang" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-box text-orange-500 w-4"></i>
                                <span>Barang yang Hilang</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="barang_hilang"
                                name="barang_hilang" 
                                x-model="formData.barang_hilang"
                                value="{{ old('barang_hilang') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Contoh: KTP, SIM, STNK, dll"
                                required
                            >
                        </div>

                        <!-- Grid for Location and Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tempat Kehilangan -->
                            <div class="space-y-2">
                                <label for="tempat_kehilangan" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-location-arrow text-red-500 w-4"></i>
                                    <span>Tempat Kehilangan</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="tempat_kehilangan"
                                    name="tempat_kehilangan" 
                                    x-model="formData.tempat_kehilangan"
                                    value="{{ old('tempat_kehilangan') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Lokasi kehilangan"
                                    required
                                >
                            </div>

                            <!-- Tanggal Kehilangan -->
                            <div class="space-y-2">
                                <label for="tanggal_kehilangan" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-calendar text-blue-500 w-4"></i>
                                    <span>Tanggal Kehilangan</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="tanggal_kehilangan"
                                    name="tanggal_kehilangan" 
                                    x-model="formData.tanggal_kehilangan"
                                    value="{{ old('tanggal_kehilangan') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                Pastikan semua data yang diisi sudah benar
                            </div>
                            
                            <div class="flex space-x-3">
                                <button 
                                    type="button"
                                    onclick="history.back()"
                                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium"
                                >
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali
                                </button>
                                
                                <button 
                                    type="submit"
                                    @click="isSubmitting = true"
                                    :disabled="isSubmitting"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': isSubmitting,
                                        'hover:bg-blue-600 hover:shadow-lg transform hover:-translate-y-0.5': !isSubmitting
                                    }"
                                    class="px-8 py-3 bg-gradient-to-r from-[#0088cc] to-blue-500 text-white rounded-lg transition-all duration-200 font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:ring-offset-2"
                                >
                                    <span x-show="!isSubmitting" class="flex items-center space-x-2">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>Kirim Permohonan</span>
                                    </span>
                                    <span x-show="isSubmitting" class="flex items-center space-x-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                        <span>Memproses...</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js Script -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
