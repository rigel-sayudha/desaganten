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
                    nama: '{{ Auth::user()->name ?? '' }}',
                    nik: '{{ Auth::user()->nik ?? '' }}',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    alamat: '',
                    jenis_barang: '',
                    waktu_tempat: ''
                },
                isSubmitting: false
            }">
                
                <!-- Alert for Login Requirement -->
                @guest
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                            <div class="flex-1">
                                <span class="text-yellow-700 font-medium text-sm">Informasi Penting:</span>
                                <p class="text-yellow-700 text-sm mt-1">
                                    Anda harus <a href="{{ route('login') }}" class="font-semibold underline">login</a> terlebih dahulu untuk mengajukan surat keterangan kehilangan. 
                                    Belum punya akun? <a href="{{ route('register') }}" class="font-semibold underline">Daftar di sini</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                @endguest

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

            <form method="POST" action="{{ route('surat.kehilangan.submit') }}" enctype="multipart/form-data" class="space-y-6" 
                x-on:submit="console.log('Form submitted'); isSubmitting = true" 
                      @if(!auth()->check())
                      x-on:submit.prevent="
                          console.log('User not authenticated, redirecting to login');
                          alert('Anda harus login terlebih dahulu untuk mengajukan surat. Anda akan diarahkan ke halaman login.');
                          window.location.href = '{{ route('login') }}';
                      "
                      @endif
                >
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
                                value="{{ Auth::user()->name ?? old('nama') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed transition duration-200 placeholder-gray-400"
                                placeholder="Nama otomatis dari profil"
                                readonly
                            >
                        </div>

                        <!-- NIK -->
                        <div class="space-y-2">
                            <label for="nik" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-id-card text-green-500 w-4"></i>
                                <span>NIK</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nik"
                                name="nik" 
                                value="{{ Auth::user()->nik }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed transition duration-200 placeholder-gray-400"
                                placeholder="NIK otomatis dari profil"
                                maxlength="16"
                                readonly
                            >
                        </div>

                        <!-- Grid for Birth Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tempat Lahir -->
                            <div class="space-y-2">
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-map-marker-alt text-red-500 w-4"></i>
                                    <span>Tempat Lahir</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="tempat_lahir"
                                    name="tempat_lahir" 
                                    value="{{ old('tempat_lahir') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Jakarta"
                                    required
                                >
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="space-y-2">
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                    <i class="fas fa-birthday-cake text-pink-500 w-4"></i>
                                    <span>Tanggal Lahir</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="tanggal_lahir"
                                    name="tanggal_lahir" 
                                    value="{{ old('tanggal_lahir') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200"
                                    required
                                >
                            </div>
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
                            <label for="jenis_barang" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-box text-orange-500 w-4"></i>
                                <span>Jenis Barang yang Hilang</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="jenis_barang"
                                name="jenis_barang" 
                                value="{{ old('jenis_barang') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Contoh: KTP, SIM, STNK, dll"
                                required
                            >
                        </div>

                        <!-- Waktu dan Tempat Kehilangan -->
                        <div class="space-y-2">
                            <label for="waktu_tempat" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-clock text-red-500 w-4"></i>
                                <span>Waktu dan Tempat Kehilangan</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="waktu_tempat"
                                name="waktu_tempat" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Jelaskan kapan dan dimana barang tersebut hilang. Contoh: Pada hari Senin, 20 Agustus 2025 sekitar pukul 14.00 WIB di area pasar tradisional Karanganyar"
                                required
                            >{{ old('waktu_tempat') }}</textarea>
                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-file-upload text-blue-500"></i>
                                <span>Dokumen Pendukung</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Upload dokumen pendukung untuk memperkuat laporan kehilangan (opsional)</p>
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
                                    id="file_ktp_pelapor"
                                    name="file_ktp_pelapor" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload scan KTP untuk verifikasi identitas pelapor</p>
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
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload scan Kartu Keluarga untuk verifikasi data</p>
                            </div>
                        </div>

                        <!-- Laporan Polisi -->
                        <div class="space-y-2">
                            <label for="file_laporan_polisi" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-file-text text-orange-500 w-4"></i>
                                <span>Bukti Kepemilikan</span>
                                <span class="text-gray-400 text-xs">(PDF, JPG, PNG - Max 2MB)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_laporan_polisi"
                                    name="file_laporan_polisi" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload surat laporan polisi kehilangan (jika ada)</p>
                            </div>
                        </div>

                        <!-- Dokumen Tambahan -->
                        <div class="space-y-2">
                            <label for="file_dokumen_tambahan" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-paperclip text-purple-500 w-4"></i>
                                <span>Dokumen Tambahan</span>
                                <span class="text-gray-400 text-xs">(PDF, JPG, PNG - Max 2MB)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_dokumen_tambahan"
                                    name="file_dokumen_tambahan" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                                >
                                <p class="text-xs text-gray-500 mt-1">Upload dokumen tambahan (bukti kepemilikan, foto barang, dll)</p>
                            </div>
                        </div>

                        <!-- Upload Guidelines -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h4 class="font-medium text-red-900 mb-2 flex items-center space-x-2">
                                <i class="fas fa-info-circle text-red-600"></i>
                                <span>Panduan Upload Dokumen</span>
                            </h4>
                            <ul class="text-red-800 text-sm space-y-1">
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-red-600 mt-0.5 w-3"></i>
                                    <span>Format file yang diterima: PDF, JPG, JPEG, PNG</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-red-600 mt-0.5 w-3"></i>
                                    <span>Ukuran maksimal setiap file: 2MB</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-red-600 mt-0.5 w-3"></i>
                                    <span>Dokumen pendukung dapat memperkuat laporan kehilangan</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-check text-red-600 mt-0.5 w-3"></i>
                                    <span>Semua dokumen bersifat opsional tetapi sangat direkomendasikan</span>
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
                                    type="submit"
                                    @if(!auth()->check())
                                        @click.prevent="
                                            alert('Silakan login terlebih dahulu untuk mengajukan surat keterangan kehilangan.');
                                            window.location.href = '{{ route('login') }}';
                                        "
                                    @else
                                        :disabled="isSubmitting"
                                        :class="{
                                            'opacity-50 cursor-not-allowed': isSubmitting,
                                            'hover:bg-blue-600 hover:shadow-lg transform hover:-translate-y-0.5': !isSubmitting
                                        }"
                                    @endif
                                    class="px-8 py-3 bg-gradient-to-r from-[#0088cc] to-blue-500 text-white rounded-lg transition-all duration-200 font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:ring-offset-2"
                                >
                                    @guest
                                        <span class="flex items-center space-x-2">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <span>Login untuk Mengajukan</span>
                                        </span>
                                    @else
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
                                    @endguest
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
