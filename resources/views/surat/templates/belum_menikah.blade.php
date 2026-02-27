@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Pelayanan Surat', 'url' => url('/surat/form')],
    ['label' => 'Surat Keterangan Belum Menikah', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])

<!-- Success/Error Alert Handler -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
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

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: `<ul style='text-align:left;'>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
                confirmButtonColor: '#ef4444',
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
            });
        });
    </script>
@endif

<!-- Modern Form Container -->
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-white to-rose-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        <!-- Form Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full shadow-lg mb-4">
                <i class="fas fa-heart text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Keterangan Belum Menikah</h1>
            <p class="text-gray-600 max-w-lg mx-auto">
                Silakan lengkapi formulir di bawah ini untuk mengajukan permohonan surat keterangan belum menikah
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-2xl shadow-xl border border-white border-opacity-50 overflow-hidden">
            
            <!-- Form Content -->
            <div class="p-8 space-y-8" x-data="{ 
                formData: {
                    nik: '{{ Auth::user()->nik }}',
                    nama: '{{ Auth::user()->name }}',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    jenis_kelamin: '',
                    agama: '',
                    pekerjaan: '',
                    no_telepon: '',
                    alamat: '',
                    nama_orang_tua: '',
                    pekerjaan_orang_tua: '',
                    alamat_orang_tua: '',
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

                <form action="{{ route('surat.belum-menikah.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8" 
                      @submit="isSubmitting = true" 
                      x-bind:class="{ 'pointer-events-none opacity-75': isSubmitting }">
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-user text-pink-500"></i>
                                <span>Data Pemohon</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi pribadi pemohon surat keterangan belum menikah</p>
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
                                value="{{ Auth::user()->nik }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed transition duration-200 placeholder-gray-400"
                                placeholder="NIK otomatis dari profil"
                                maxlength="16"
                                readonly
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
                                value="{{ Auth::user()->name ?? old('nama') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed transition duration-200 placeholder-gray-400"
                                placeholder="Nama otomatis dari profil"
                                readonly
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200 placeholder-gray-400"
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
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Jenis Kelamin & Agama -->
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200"
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
                        </div>

                        <!-- Pekerjaan & No Telepon -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Wiraswasta, PNS, Mahasiswa"
                                    required
                                >
                            </div>
                            <div class="space-y-2">
                                <label for="no_telepon" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-phone text-blue-500 w-4"></i>
                                    <span>No. Telepon</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="no_telepon"
                                    name="no_telepon" 
                                    x-model="formData.no_telepon"
                                    value="{{ old('no_telepon') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: 08123456789"
                                    min="0"
                                    oninput="if(this.value.length > 15) this.value = this.value.slice(0,15);"
                                >
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="space-y-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-map-marker-alt text-pink-500 w-4"></i>
                                <span>Alamat Lengkap</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="alamat"
                                name="alamat" 
                                x-model="formData.alamat"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Masukkan alamat lengkap tempat tinggal"
                                required
                            >{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <!-- Parents Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-users text-blue-500"></i>
                                <span>Data Orang Tua</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi orang tua atau wali pemohon</p>
                        </div>

                        <!-- Nama Orang Tua & Pekerjaan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="nama_orang_tua" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-user text-blue-500 w-4"></i>
                                    <span>Nama Orang Tua / Wali</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="nama_orang_tua"
                                    name="nama_orang_tua" 
                                    x-model="formData.nama_orang_tua"
                                    value="{{ old('nama_orang_tua') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Masukkan nama orang tua / wali"
                                    required
                                >
                            </div>
                            <div class="space-y-2">
                                <label for="pekerjaan_orang_tua" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-briefcase text-indigo-500 w-4"></i>
                                    <span>Pekerjaan Orang Tua / Wali</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pekerjaan_orang_tua"
                                    name="pekerjaan_orang_tua" 
                                    x-model="formData.pekerjaan_orang_tua"
                                    value="{{ old('pekerjaan_orang_tua') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Petani, Wiraswasta"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Alamat Orang Tua -->
                        <div class="space-y-2">
                            <label for="alamat_orang_tua" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-map-marker-alt text-blue-500 w-4"></i>
                                <span>Alamat Orang Tua / Wali</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="alamat_orang_tua"
                                name="alamat_orang_tua" 
                                x-model="formData.alamat_orang_tua"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Masukkan alamat lengkap orang tua / wali"
                                required
                            >{{ old('alamat_orang_tua') }}</textarea>
                        </div>
                    </div>

                    <!-- Purpose Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-clipboard-list text-yellow-500"></i>
                                <span>Keperluan</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Tujuan penggunaan surat keterangan belum menikah</p>
                        </div>

                        <!-- Keperluan -->
                        <div class="space-y-2">
                            <label for="keperluan" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-clipboard-list text-yellow-500 w-4"></i>
                                <span>Untuk keperluan apa surat keterangan belum menikah ini?</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="keperluan"
                                name="keperluan" 
                                x-model="formData.keperluan"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Contoh: Untuk keperluan melamar pekerjaan di instansi pemerintah"
                                required
                            >{{ old('keperluan') }}</textarea>
                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-file-upload text-blue-500"></i>
                                <span>Dokumen Pendukung</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Upload dokumen pendukung untuk mempercepat proses verifikasi (opsional)</p>
                        </div>

                        <!-- KTP/Identitas -->
                        <div class="space-y-2">
                            <label for="ktp_file" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-id-card text-indigo-500 w-4"></i>
                                <span>Scan KTP/Identitas</span>
                                <span class="text-gray-400 text-xs">(PDF, JPG, PNG - Max 2MB)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="ktp_file"
                                    name="ktp_file" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload scan KTP untuk verifikasi identitas</p>
                            </div>
                        </div>

                        <!-- Kartu Keluarga -->
                        <div class="space-y-2">
                            <label for="kk_file" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-users text-purple-500 w-4"></i>
                                <span>Scan Kartu Keluarga</span>
                                <span class="text-gray-400 text-xs">(PDF, JPG, PNG - Max 2MB)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="kk_file"
                                    name="kk_file" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload scan Kartu Keluarga untuk verifikasi status</p>
                            </div>
                        </div>

                        <!-- Akta Kelahiran -->
                        <div class="space-y-2">
                            <label for="akta_file" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-certificate text-green-500 w-4"></i>
                                <span>Scan Akta Kelahiran</span>
                                <span class="text-gray-400 text-xs">(PDF, JPG, PNG - Max 2MB)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="akta_file"
                                    name="akta_file" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload scan Akta Kelahiran untuk verifikasi data</p>
                            </div>
                        </div>

                        <!-- Upload Guidelines -->
                        <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                            <h4 class="font-medium text-pink-900 mb-2 flex items-center space-x-2">
                                <i class="fas fa-info-circle text-pink-600"></i>
                                <span>Panduan Upload Dokumen</span>
                            </h4>
                            <ul class="text-pink-800 text-sm space-y-1">
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-pink-600 mt-0.5 w-3"></i>
                                    <span>Format file yang diterima: PDF, JPG, JPEG, PNG</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-pink-600 mt-0.5 w-3"></i>
                                    <span>Ukuran maksimal setiap file: 2MB</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-pink-600 mt-0.5 w-3"></i>
                                    <span>Pastikan dokumen terlihat jelas dan dapat dibaca</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-pink-600 mt-0.5 w-3"></i>
                                    <span>Upload dokumen dapat mempercepat proses verifikasi</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Declaration Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-file-signature text-green-500"></i>
                                <span>Pernyataan</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Pernyataan kebenaran data dan persetujuan</p>
                        </div>

                        <!-- Declaration Checkbox -->
                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <input 
                                        type="checkbox" 
                                        id="pernyataan"
                                        name="pernyataan" 
                                        value="1"
                                        {{ old('pernyataan') ? 'checked' : '' }}
                                        class="w-5 h-5 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500 focus:ring-2 mt-1"
                                        required
                                    >
                                    <label for="pernyataan" class="text-sm text-gray-700 leading-relaxed">
                                        <span class="font-medium text-gray-900">Saya menyatakan bahwa:</span>
                                        <ul class="mt-2 space-y-1 list-disc list-inside ml-4">
                                            <li>Semua data yang saya isi adalah benar dan dapat dipertanggungjawabkan</li>
                                            <li>Saya belum pernah menikah dan belum pernah terikat dalam perkawinan yang sah</li>
                                            <li>Jika dikemudian hari terbukti data yang saya berikan tidak benar, saya bersedia menerima sanksi sesuai ketentuan yang berlaku</li>
                                            <li>Saya memberikan persetujuan kepada Pemerintah Desa Ganten untuk memproses data pribadi saya untuk keperluan penerbitan surat keterangan belum menikah</li>
                                        </ul>
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-info-circle text-pink-500 mr-1"></i>
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
                                        'hover:bg-pink-600 hover:shadow-lg transform hover:-translate-y-0.5': !isSubmitting
                                    }"
                                    class="px-8 py-3 bg-gradient-to-r from-pink-500 to-rose-600 text-white rounded-lg transition-all duration-200 font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2"
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
