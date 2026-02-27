@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Pelayanan Surat', 'url' => url('/surat/form')],
    ['label' => 'Surat Keterangan Tidak Mampu', 'url' => '#'],
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
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        <!-- Form Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-red-500 to-orange-600 rounded-full shadow-lg mb-4">
                <i class="fas fa-hand-holding-heart text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Keterangan Tidak Mampu</h1>
            <p class="text-gray-600 max-w-lg mx-auto">
                Silakan isi data kondisi ekonomi keluarga dengan lengkap dan benar untuk memproses surat keterangan tidak mampu
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-2xl shadow-xl border border-white border-opacity-50 overflow-hidden">
            
            <!-- Form Content -->
            <div class="p-8 space-y-8" x-data="{ 
                formData: {
                    nama: '{{ Auth::user()->name ?? '' }}'
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

                <form x-ref="form" method="POST" action="{{ route('surat.tidak-mampu.submit') }}" class="space-y-8">
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-user text-red-500"></i>
                                <span>Data Pemohon</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi pribadi pemohon surat keterangan tidak mampu</p>
                        </div>

                        <!-- Grid for Personal Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                    value="{{ Auth::user()->name ?? old('nama') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed transition duration-200 placeholder-gray-400"
                                    placeholder="Nama otomatis dari profil"
                                    readonly
                                >
                                @error('nama')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                    value="{{ Auth::user()->nik }}"
                                    maxlength="16" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed transition duration-200 placeholder-gray-400"
                                    placeholder="NIK otomatis dari profil"
                                    readonly
                                >
                                @error('nik')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat & Tanggal Lahir -->
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
                                    value="{{ old('tempat_lahir') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Jakarta"
                                    required
                                >
                                @error('tempat_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                    value="{{ old('tanggal_lahir') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                @error('tanggal_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
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
                                @error('agama')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                    value="{{ old('pekerjaan') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Buruh Harian / Tidak Bekerja"
                                    required
                                >
                                @error('pekerjaan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Penghasilan -->
                            <div class="space-y-2">
                                <label for="penghasilan" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-coins text-yellow-500 w-4"></i>
                                    <span>Penghasilan per Bulan</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="penghasilan"
                                    name="penghasilan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Penghasilan</option>
                                    <option value="Tidak Ada" {{ old('penghasilan') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                    <option value="< Rp 500.000" {{ old('penghasilan') == '< Rp 500.000' ? 'selected' : '' }}>< Rp 500.000</option>
                                    <option value="Rp 500.000 - Rp 1.000.000" {{ old('penghasilan') == 'Rp 500.000 - Rp 1.000.000' ? 'selected' : '' }}>Rp 500.000 - Rp 1.000.000</option>
                                    <option value="Rp 1.000.000 - Rp 1.500.000" {{ old('penghasilan') == 'Rp 1.000.000 - Rp 1.500.000' ? 'selected' : '' }}>Rp 1.000.000 - Rp 1.500.000</option>
                                </select>
                                @error('penghasilan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
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
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Masukkan alamat lengkap tempat tinggal"
                                required
                            >{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Family Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-home text-purple-500"></i>
                                <span>Data Keluarga</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi kondisi ekonomi dan keluarga</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jumlah Tanggungan -->
                            <div class="space-y-2">
                                <label for="jumlah_tanggungan" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-users text-blue-500 w-4"></i>
                                    <span>Jumlah Tanggungan Keluarga</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="jumlah_tanggungan"
                                    name="jumlah_tanggungan" 
                                    value="{{ old('jumlah_tanggungan') }}"
                                    min="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: 3"
                                    required
                                >
                                @error('jumlah_tanggungan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Rumah -->
                            <div class="space-y-2">
                                <label for="status_rumah" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-house-user text-orange-500 w-4"></i>
                                    <span>Status Tempat Tinggal</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="status_rumah"
                                    name="status_rumah" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Status</option>
                                    <option value="Milik Sendiri" {{ old('status_rumah') == 'Milik Sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                                    <option value="Sewa/Kontrak" {{ old('status_rumah') == 'Sewa/Kontrak' ? 'selected' : '' }}>Sewa/Kontrak</option>
                                    <option value="Menumpang" {{ old('status_rumah') == 'Menumpang' ? 'selected' : '' }}>Menumpang</option>
                                    <option value="Lainnya" {{ old('status_rumah') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('status_rumah')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Keterangan Ekonomi -->
                        <div class="space-y-2">
                            <label for="keterangan_ekonomi" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-chart-line text-green-500 w-4"></i>
                                <span>Keterangan Kondisi Ekonomi Keluarga</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="keterangan_ekonomi"
                                name="keterangan_ekonomi" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Jelaskan kondisi ekonomi keluarga secara detail"
                                required
                            >{{ old('keterangan_ekonomi') }}</textarea>
                            @error('keterangan_ekonomi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Purpose Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-clipboard-list text-yellow-500"></i>
                                <span>Keperluan</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Tujuan penggunaan surat keterangan tidak mampu</p>
                        </div>

                        <div class="space-y-2">
                            <label for="keperluan" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-question-circle text-blue-500 w-4"></i>
                                <span>Untuk keperluan apa surat keterangan tidak mampu ini?</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="keperluan"
                                name="keperluan" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Contoh: Untuk mendapatkan bantuan pendidikan anak, beasiswa, bantuan sosial, dll"
                                required
                            >{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row gap-4 justify-between">
                            <a 
                                href="{{ url('/surat/form') }}" 
                                class="flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button 
                                type="button"
                                @click="submitForm()"
                                :disabled="isSubmitting"
                                class="flex-1 sm:flex-initial bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg hover:shadow-xl"
                            >
                                <span x-show="!isSubmitting" class="flex items-center justify-center space-x-2">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Ajukan Surat Keterangan Tidak Mampu</span>
                                </span>
                                <span x-show="isSubmitting" class="flex items-center justify-center space-x-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Sedang memproses...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js Script -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        function submitForm() {
            return {
                isSubmitting: false,
                
                submitForm() {
                    if (this.isSubmitting) return;
                    
                    // Validate required fields
                    const form = document.getElementById('tidak-mampu-form');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }
                    
                    this.isSubmitting = true;
                    form.submit();
                }
            }
        }

        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        @endif
    </script>
@endsection
