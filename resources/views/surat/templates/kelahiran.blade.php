@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'Kelahiran', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-50 to-cyan-100 rounded-t-2xl p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-white/20 backdrop-blur-sm"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-full shadow-lg mb-4">
                    <i class="fas fa-baby text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Keterangan Kelahiran</h1>
                <p class="text-lg text-gray-600">Silakan isi formulir berikut dengan data yang valid dan lengkap</p>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-b-2xl shadow-xl border-t-4 border-blue-600">
            <div class="p-8">
                <form 
                    id="kelahiran-form"
                    method="POST" 
                    action="{{ route('surat.kelahiran.submit') }}" 
                    x-data="submitForm()"
                    @submit.prevent="submitForm()"
                    class="space-y-8"
                >
                    @csrf

                    <!-- Child Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-baby text-blue-500"></i>
                                <span>Data Anak</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi mengenai anak yang baru lahir</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Anak -->
                            <div class="space-y-2">
                                <label for="nama_anak" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-baby text-blue-500 w-4"></i>
                                    <span>Nama Lengkap Anak</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="nama_anak"
                                    name="nama_anak" 
                                    value="{{ old('nama_anak') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Masukkan nama lengkap anak"
                                    required
                                >
                                @error('nama_anak')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Anak ke -->
                            <div class="space-y-2">
                                <label for="anak_ke" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-sort-numeric-up text-purple-500 w-4"></i>
                                    <span>Anak ke</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="anak_ke"
                                    name="anak_ke" 
                                    value="{{ old('anak_ke') }}"
                                    min="1"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: 1"
                                    required
                                >
                                @error('anak_ke')
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
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

                            <!-- Tempat Lahir Anak -->
                            <div class="space-y-2">
                                <label for="tempat_lahir_anak" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-hospital text-green-500 w-4"></i>
                                    <span>Dilahirkan di</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="tempat_lahir_anak"
                                    name="tempat_lahir_anak" 
                                    value="{{ old('tempat_lahir_anak') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: RS. Umum Karanganyar"
                                    required
                                >
                                @error('tempat_lahir_anak')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat Anak -->
                        <div class="space-y-2">
                            <label for="alamat_anak" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-home text-orange-500 w-4"></i>
                                <span>Alamat Anak</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="alamat_anak"
                                name="alamat_anak" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Contoh: Jl. Raya No. 123, RT 01/RW 02, Kelurahan..."
                                required
                            >{{ old('alamat_anak') }}</textarea>
                            @error('alamat_anak')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Assistant Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Penolong Kelahiran -->
                            <div class="space-y-2">
                                <label for="penolong_kelahiran" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-user-md text-blue-500 w-4"></i>
                                    <span>Penolong Kelahiran</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="penolong_kelahiran"
                                    name="penolong_kelahiran" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Penolong</option>
                                    <option value="Bidan" {{ old('penolong_kelahiran') == 'Bidan' ? 'selected' : '' }}>Bidan</option>
                                    <option value="Dokter" {{ old('penolong_kelahiran') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                                    <option value="Dukun" {{ old('penolong_kelahiran') == 'Dukun' ? 'selected' : '' }}>Dukun</option>
                                </select>
                                @error('penolong_kelahiran')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat Bidan -->
                            <div class="space-y-2">
                                <label for="alamat_bidan" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-map-marker-alt text-red-500 w-4"></i>
                                    <span>Alamat Bidan Penolong</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="alamat_bidan"
                                    name="alamat_bidan" 
                                    value="{{ old('alamat_bidan') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Alamat lengkap bidan/dokter penolong"
                                    required
                                >
                                @error('alamat_bidan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Mother Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-female text-pink-500"></i>
                                <span>Data Ibu</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi mengenai ibu kandung</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIK Ibu -->
                            <div class="space-y-2">
                                <label for="ibu_nik" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-id-card text-blue-500 w-4"></i>
                                    <span>NIK Ibu</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="ibu_nik"
                                    name="ibu_nik" 
                                    value="{{ old('ibu_nik') }}"
                                    maxlength="16"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="16 digit NIK ibu"
                                    required
                                >
                                @error('ibu_nik')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Ibu -->
                            <div class="space-y-2">
                                <label for="ibu_nama" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-user text-purple-500 w-4"></i>
                                    <span>Nama Ibu</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="ibu_nama"
                                    name="ibu_nama" 
                                    value="{{ old('ibu_nama') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Nama lengkap ibu"
                                    required
                                >
                                @error('ibu_nama')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat Lahir Ibu -->
                            <div class="space-y-2">
                                <label for="ibu_tempat_lahir" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-map-marker-alt text-orange-500 w-4"></i>
                                    <span>Tempat Lahir Ibu</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="ibu_tempat_lahir"
                                    name="ibu_tempat_lahir" 
                                    value="{{ old('ibu_tempat_lahir') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Karanganyar"
                                    required
                                >
                                @error('ibu_tempat_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir Ibu -->
                            <div class="space-y-2">
                                <label for="ibu_tanggal_lahir" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-calendar text-green-500 w-4"></i>
                                    <span>Tanggal Lahir Ibu</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="ibu_tanggal_lahir"
                                    name="ibu_tanggal_lahir" 
                                    value="{{ old('ibu_tanggal_lahir') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                @error('ibu_tanggal_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat Ibu -->
                        <div class="space-y-2">
                            <label for="ibu_alamat" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-home text-orange-500 w-4"></i>
                                <span>Alamat Ibu</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="ibu_alamat"
                                name="ibu_alamat" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Alamat lengkap ibu"
                                required
                            >{{ old('ibu_alamat') }}</textarea>
                            @error('ibu_alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Father Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-male text-blue-500"></i>
                                <span>Data Ayah</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi mengenai ayah kandung</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIK Ayah -->
                            <div class="space-y-2">
                                <label for="ayah_nik" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-id-card text-blue-500 w-4"></i>
                                    <span>NIK Ayah</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="ayah_nik"
                                    name="ayah_nik" 
                                    value="{{ old('ayah_nik') }}"
                                    maxlength="16"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="16 digit NIK ayah"
                                    required
                                >
                                @error('ayah_nik')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Ayah -->
                            <div class="space-y-2">
                                <label for="ayah_nama" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-user text-purple-500 w-4"></i>
                                    <span>Nama Ayah</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="ayah_nama"
                                    name="ayah_nama" 
                                    value="{{ old('ayah_nama') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Nama lengkap ayah"
                                    required
                                >
                                @error('ayah_nama')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat Lahir Ayah -->
                            <div class="space-y-2">
                                <label for="ayah_tempat_lahir" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-map-marker-alt text-orange-500 w-4"></i>
                                    <span>Tempat Lahir Ayah</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="ayah_tempat_lahir"
                                    name="ayah_tempat_lahir" 
                                    value="{{ old('ayah_tempat_lahir') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Karanganyar"
                                    required
                                >
                                @error('ayah_tempat_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir Ayah -->
                            <div class="space-y-2">
                                <label for="ayah_tanggal_lahir" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-calendar text-green-500 w-4"></i>
                                    <span>Tanggal Lahir Ayah</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="ayah_tanggal_lahir"
                                    name="ayah_tanggal_lahir" 
                                    value="{{ old('ayah_tanggal_lahir') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                @error('ayah_tanggal_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat Ayah -->
                        <div class="space-y-2">
                            <label for="ayah_alamat" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-home text-orange-500 w-4"></i>
                                <span>Alamat Ayah</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="ayah_alamat"
                                name="ayah_alamat" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Alamat lengkap ayah"
                                required
                            >{{ old('ayah_alamat') }}</textarea>
                            @error('ayah_alamat')
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
                                type="submit" 
                                :disabled="isSubmitting"
                                class="flex-1 sm:flex-initial bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg hover:shadow-xl"
                            >
                                <span x-show="!isSubmitting" class="flex items-center justify-center space-x-2">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Ajukan Surat Keterangan Kelahiran</span>
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
                    const form = document.getElementById('kelahiran-form');
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
                confirmButtonColor: '#3b82f6'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6'
            });
        @endif
    </script>
@endsection
