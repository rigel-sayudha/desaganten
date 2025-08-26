@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Pelayanan Surat', 'url' => url('/surat/form')],
    ['label' => 'Surat Keterangan Domisili', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])

<!-- Success Alert Handler -->
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

<!-- Modern Form Container -->
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        <!-- Form Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full shadow-lg mb-4">
                <i class="fas fa-home text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Keterangan Domisili</h1>
            <p class="text-gray-600 max-w-lg mx-auto">
                Silakan lengkapi formulir di bawah ini untuk mengajukan permohonan surat keterangan domisili
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-2xl shadow-xl border border-white border-opacity-50 overflow-hidden">
            
            <!-- Form Content -->
            <div class="p-8 space-y-8" x-data="{ 
                formData: {
                    nik: '',
                    nama: '',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    jenis_kelamin: '',
                    kewarganegaraan: '',
                    agama: '',
                    status: '',
                    pekerjaan: '',
                    alamat: '',
                    keperluan: ''
                },
                isSubmitting: false
            }">
                
                <!-- Alert for Error Messages -->
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

                <form action="{{ route('surat.domisili.submit') }}" method="POST" class="space-y-8" 
                      @submit="isSubmitting = true" 
                      x-bind:class="{ 'pointer-events-none opacity-75': isSubmitting }">
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-user text-green-500"></i>
                                <span>Data Pemohon</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi pribadi pemohon surat keterangan domisili</p>
                        </div>

                        <!-- NIK -->
                        <div class="space-y-2">
                            <label for="nik" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-id-card text-blue-500 w-4"></i>
                                <span>NIK (Nomor Induk Kependudukan)</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nik"
                                name="nik" 
                                x-model="formData.nik"
                                value="{{ old('nik') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Masukkan 16 digit NIK"
                                maxlength="16"
                                required
                            >
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="space-y-2">
                            <label for="nama" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-user text-purple-500 w-4"></i>
                                <span>Nama Lengkap</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nama"
                                name="nama" 
                                x-model="formData.nama"
                                value="{{ old('nama') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Masukkan nama lengkap sesuai KTP"
                                required
                            >
                        </div>

                        <!-- Tempat & Tanggal Lahir -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="tempat_lahir" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-map-marker-alt text-orange-500 w-4"></i>
                                    <span>Tempat Lahir</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="tempat_lahir"
                                    name="tempat_lahir" 
                                    x-model="formData.tempat_lahir"
                                    value="{{ old('tempat_lahir') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Jakarta"
                                    required
                                >
                            </div>
                            <div class="space-y-2">
                                <label for="tanggal_lahir" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-calendar text-blue-500 w-4"></i>
                                    <span>Tanggal Lahir</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="tanggal_lahir"
                                    name="tanggal_lahir" 
                                    x-model="formData.tanggal_lahir"
                                    value="{{ old('tanggal_lahir') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Jenis Kelamin & Kewarganegaraan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-venus-mars text-pink-500 w-4"></i>
                                    <span>Jenis Kelamin</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="jenis_kelamin"
                                    name="jenis_kelamin" 
                                    x-model="formData.jenis_kelamin"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="kewarganegaraan" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-flag text-red-500 w-4"></i>
                                    <span>Kewarganegaraan</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="kewarganegaraan"
                                    name="kewarganegaraan" 
                                    x-model="formData.kewarganegaraan"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Kewarganegaraan</option>
                                    <option value="WNI" {{ old('kewarganegaraan') == 'WNI' ? 'selected' : '' }}>WNI</option>
                                    <option value="WNA" {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA</option>
                                </select>
                            </div>
                        </div>

                        <!-- Agama & Status Perkawinan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="agama" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-pray text-green-500 w-4"></i>
                                    <span>Agama</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="agama"
                                    name="agama" 
                                    x-model="formData.agama"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-heart text-red-500 w-4"></i>
                                    <span>Status Perkawinan</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="status"
                                    name="status" 
                                    x-model="formData.status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Status Perkawinan</option>
                                    <option value="Belum Kawin" {{ old('status') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                    <option value="Kawin" {{ old('status') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                    <option value="Cerai Hidup" {{ old('status') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                    <option value="Cerai Mati" {{ old('status') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pekerjaan -->
                        <div class="space-y-2">
                            <label for="pekerjaan" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-briefcase text-indigo-500 w-4"></i>
                                <span>Pekerjaan</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="pekerjaan"
                                name="pekerjaan" 
                                x-model="formData.pekerjaan"
                                value="{{ old('pekerjaan') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Contoh: Wiraswasta, PNS, Mahasiswa"
                                required
                            >
                        </div>

                        <!-- Alamat Domisili -->
                        <div class="space-y-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-map-marker-alt text-green-500 w-4"></i>
                                <span>Alamat Domisili</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="alamat"
                                name="alamat" 
                                x-model="formData.alamat"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Masukkan alamat domisili lengkap"
                                required
                            >{{ old('alamat') }}</textarea>
                        </div>

                        <!-- Keperluan -->
                        <div class="space-y-2">
                            <label for="keperluan" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-clipboard-list text-yellow-500 w-4"></i>
                                <span>Keperluan</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="keperluan"
                                name="keperluan" 
                                x-model="formData.keperluan"
                                value="{{ old('keperluan') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Contoh: Pendaftaran sekolah, Melamar pekerjaan"
                                required
                            >
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-info-circle text-green-500 mr-1"></i>
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
                                    x-bind:disabled="isSubmitting"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': isSubmitting,
                                        'hover:bg-green-600 hover:shadow-lg transform hover:-translate-y-0.5': !isSubmitting
                                    }"
                                    class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg transition-all duration-200 font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
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

        <!-- Information Card -->
        <div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-6">
            <h4 class="font-semibold text-green-900 mb-3 flex items-center space-x-2">
                <i class="fas fa-lightbulb text-green-600"></i>
                <span>Informasi Penting</span>
            </h4>
            <ul class="text-green-800 text-sm space-y-2">
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check text-green-600 mt-0.5"></i>
                    <span>Pastikan semua data yang diisi sesuai dengan dokumen resmi</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check text-green-600 mt-0.5"></i>
                    <span>Surat keterangan domisili akan diproses dalam 1-3 hari kerja</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check text-green-600 mt-0.5"></i>
                    <span>Anda akan dihubungi untuk pengambilan surat</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Alpine.js Script -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
