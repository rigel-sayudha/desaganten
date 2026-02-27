@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Pelayanan Surat', 'url' => url('/surat/form')],
    ['label' => 'Surat Keterangan KTP', 'url' => '#'],
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
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
            });
        });
    </script>
@endif

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                html: `<ul style='text-align:left;'>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endif

<!-- Modern Form Container -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        <!-- Form Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full shadow-lg mb-4">
                <i class="fas fa-id-card text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Keterangan KTP</h1>
            <p class="text-gray-600 max-w-lg mx-auto">
                Silakan isi data permohonan KTP dengan lengkap dan benar untuk memproses surat keterangan
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-2xl shadow-xl border border-white border-opacity-50 overflow-hidden">
            
            <!-- Form Content -->
            <div class="p-8 space-y-8" x-data="{ 
                formData: {
                    nama_lengkap: '{{ Auth::user()->name }}',
                    jenis_kelamin: '',
                    agama: '',
                    status_perkawinan: '',
                    nik: '{{ Auth::user()->nik }}',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    pekerjaan: '',
                    alamat: '',
                    keperluan: ''
                },
                isSubmitting: false,
                submitForm() {
                    this.isSubmitting = true;
                    this.$refs.form.submit();
                },
                resetSubmission() {
                    this.isSubmitting = false;
                }
            }" x-init="
                // Reset submission state if form errors exist
                if({{ $errors->any() ? 'true' : 'false' }}) {
                    resetSubmission();
                }
            ">

                <form x-ref="form" method="POST" action="{{ route('surat.ktp.submit') }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-user text-blue-500"></i>
                                <span>Data Pemohon</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi pribadi pemohon surat keterangan KTP</p>
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
                                maxlength="16" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed transition duration-200 placeholder-gray-400"
                                placeholder="NIK otomatis dari profil"
                                readonly
                            >
                            <p class="text-xs text-gray-500">*NIK harus 16 digit angka</p>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="space-y-2">
                            <label for="nama_lengkap" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-user text-purple-500 w-4"></i>
                                <span>Nama Lengkap</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nama_lengkap"
                                name="nama_lengkap" 
                                x-model="formData.nama_lengkap"
                                value="{{ Auth::user()->name ?? old('nama_lengkap') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed transition duration-200 placeholder-gray-400"
                                placeholder="Nama otomatis dari profil"
                                readonly
                            >
                        </div>

                        <!-- Grid for Gender and Religion -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jenis Kelamin -->
                            <div class="space-y-2">
                                <label for="jenis_kelamin" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-venus-mars text-pink-500 w-4"></i>
                                    <span>Jenis Kelamin</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="jenis_kelamin"
                                    name="jenis_kelamin" 
                                    x-model="formData.jenis_kelamin"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <!-- Agama -->
                            <div class="space-y-2">
                                <label for="agama" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-pray text-green-500 w-4"></i>
                                    <span>Agama</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="agama"
                                    name="agama" 
                                    x-model="formData.agama"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
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

                        <!-- Status Perkawinan -->
                        <div class="space-y-2">
                            <label for="status_perkawinan" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-heart text-red-500 w-4"></i>
                                <span>Status Perkawinan</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="status_perkawinan"
                                name="status_perkawinan" 
                                x-model="formData.status_perkawinan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                required
                            >
                                <option value="">Pilih Status Perkawinan</option>
                                <option value="Belum Kawin" {{ old('status_perkawinan') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                <option value="Kawin" {{ old('status_perkawinan') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                <option value="Cerai Hidup" {{ old('status_perkawinan') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                <option value="Cerai Mati" {{ old('status_perkawinan') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                            </select>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Pekerjaan -->
                        <div class="space-y-2">
                            <label for="pekerjaan" class="block text-sm font-medium text-black flex items-center space-x-2">
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Contoh: Wiraswasta, PNS, Mahasiswa"
                                required
                            >
                        </div>

                        <!-- Alamat -->
                        <div class="space-y-2">
                            <label for="alamat" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-map-marker-alt text-green-500 w-4"></i>
                                <span>Alamat Lengkap</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="alamat"
                                name="alamat" 
                                x-model="formData.alamat"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Masukkan alamat lengkap sesuai KTP"
                                required
                            >{{ old('alamat') }}</textarea>
                        </div>

                        <!-- Keperluan -->
                        <div class="space-y-2">
                            <label for="keperluan" class="block text-sm font-medium text-black flex items-center space-x-2">
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Contoh: Pembuatan rekening bank, Pendaftaran sekolah"
                                required
                            >
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

                        <!-- KTP Lama -->
                        <div class="space-y-2">
                            <label for="file_ktp" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-id-card text-red-500 w-4"></i>
                                <span>Scan KTP Lama</span>
                                <span class="text-gray-400 text-xs">(PDF, JPG, PNG - Max 2MB)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_ktp"
                                    name="file_ktp" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload scan KTP lama (jika ada) untuk referensi</p>
                            </div>
                        </div>

                        <!-- Kartu Keluarga -->
                        <div class="space-y-2">
                            <label for="file_kk" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-users text-purple-500 w-4"></i>
                                <span>Scan Kartu Keluarga</span>
                                <span class="text-gray-400 text-xs">(PDF, JPG, PNG - Max 2MB)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_kk"
                                    name="file_kk" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload scan Kartu Keluarga untuk verifikasi data</p>
                            </div>
                        </div>

                        <!-- Dokumen Tambahan -->
                        <div class="space-y-2">
                            <label for="file_dokumen_tambahan" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-certificate text-green-500 w-4"></i>
                                <span>Scan Dokumen Tambahan</span>
                                <span class="text-gray-400 text-xs">(PDF, JPG, PNG - Max 2MB)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_dokumen_tambahan"
                                    name="file_dokumen_tambahan" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload dokumen tambahan seperti Akta Kelahiran, Ijazah, atau dokumen pendukung lainnya</p>
                            </div>
                        </div>

                        <!-- Upload Guidelines -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-900 mb-2 flex items-center space-x-2">
                                <i class="fas fa-info-circle text-blue-600"></i>
                                <span>Panduan Upload Dokumen</span>
                            </h4>
                            <ul class="text-blue-800 text-sm space-y-1">
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5 w-3"></i>
                                    <span>Format file yang diterima: PDF, JPG, JPEG, PNG</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5 w-3"></i>
                                    <span>Ukuran maksimal setiap file: 2MB</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5 w-3"></i>
                                    <span>Pastikan dokumen terlihat jelas dan dapat dibaca</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-blue-600 mt-0.5 w-3"></i>
                                    <span>Upload dokumen dapat mempercepat proses verifikasi</span>
                                </li>
                            </ul>
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
                                    type="button"
                                    @click="submitForm()"
                                    :disabled="isSubmitting"
                                    :class="{
                                        'opacity-50 cursor-not-allowed': isSubmitting,
                                        'hover:bg-blue-600 hover:shadow-lg transform hover:-translate-y-0.5': !isSubmitting
                                    }"
                                    class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg transition-all duration-200 font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
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
