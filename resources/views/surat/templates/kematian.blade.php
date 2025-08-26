@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'Kematian', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-red-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-50 to-pink-100 rounded-t-2xl p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-white/20 backdrop-blur-sm"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-700 rounded-full shadow-lg mb-4">
                    <i class="fas fa-cross text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Keterangan Kematian</h1>
                <p class="text-lg text-gray-600">Silakan isi formulir berikut dengan data yang valid dan lengkap</p>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-b-2xl shadow-xl border-t-4 border-red-700">
            <div class="p-8">
                <form 
                    id="kematian-form"
                    method="POST" 
                    action="{{ route('surat.kematian.submit') }}" 
                    x-data="submitForm()"
                    @submit.prevent="submitForm()"
                    class="space-y-8"
                >
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-user text-purple-500"></i>
                                <span>Data Almarhum/Almarhumah</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi pribadi yang telah meninggal dunia</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div class="space-y-2">
                                <label for="nama" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-user text-purple-500 w-4"></i>
                                    <span>Nama Almarhum/Almarhumah</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="nama"
                                    name="nama" 
                                    value="{{ old('nama') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Masukkan nama lengkap almarhum/almarhumah"
                                    required
                                >
                                @error('nama')
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

                            <!-- Tempat Lahir -->
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
                                    placeholder="Contoh: Karanganyar"
                                    required
                                >
                                @error('tempat_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="space-y-2">
                                <label for="tanggal_lahir" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-calendar text-green-500 w-4"></i>
                                    <span>Tanggal Lahir</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="tanggal_lahir"
                                    name="tanggal_lahir" 
                                    value="{{ old('tanggal_lahir') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                @error('tanggal_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kewarganegaraan -->
                            <div class="space-y-2">
                                <label for="kewarganegaraan" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-flag text-red-500 w-4"></i>
                                    <span>Kewarganegaraan</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="kewarganegaraan"
                                    name="kewarganegaraan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Kewarganegaraan</option>
                                    <option value="WNI" {{ old('kewarganegaraan') == 'WNI' ? 'selected' : '' }}>WNI (Warga Negara Indonesia)</option>
                                    <option value="WNA" {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA (Warga Negara Asing)</option>
                                </select>
                                @error('kewarganegaraan')
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

                            <!-- Status Perkawinan -->
                            <div class="space-y-2">
                                <label for="status_perkawinan" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-heart text-pink-500 w-4"></i>
                                    <span>Status Perkawinan</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="status_perkawinan"
                                    name="status_perkawinan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Status</option>
                                    <option value="Kawin" {{ old('status_perkawinan') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                    <option value="Belum Kawin" {{ old('status_perkawinan') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                    <option value="Cerai" {{ old('status_perkawinan') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                                    <option value="Mati" {{ old('status_perkawinan') == 'Mati' ? 'selected' : '' }}>Mati</option>
                                </select>
                                @error('status_perkawinan')
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
                                    placeholder="Contoh: Pegawai Swasta / Pensiunan"
                                    required
                                >
                                @error('pekerjaan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="space-y-2">
                            <label for="alamat" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-home text-orange-500 w-4"></i>
                                <span>Alamat Lengkap</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="alamat"
                                name="alamat" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Contoh: Jl. Raya No. 123, RT 01/RW 02, Kelurahan..."
                                required
                            >{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- RT/RW -->
                        <div class="space-y-2">
                            <label for="rt_rw" class="block text-sm font-medium text-black flex items-center space-x-2">
                                <i class="fas fa-map text-blue-500 w-4"></i>
                                <span>RT/RW</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="rt_rw"
                                name="rt_rw" 
                                value="{{ old('rt_rw') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Contoh: 001/002"
                                required
                            >
                            @error('rt_rw')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Death Information Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-cross text-red-500"></i>
                                <span>Keterangan Meninggal Dunia</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi mengenai waktu dan tempat meninggal dunia</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Hari -->
                            <div class="space-y-2">
                                <label for="hari" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-calendar-day text-red-500 w-4"></i>
                                    <span>Hari Meninggal</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="hari"
                                    name="hari" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                                </select>
                                @error('hari')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Meninggal -->
                            <div class="space-y-2">
                                <label for="tanggal_meninggal" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-calendar text-red-500 w-4"></i>
                                    <span>Tanggal Meninggal</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="tanggal_meninggal"
                                    name="tanggal_meninggal" 
                                    value="{{ old('tanggal_meninggal') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                @error('tanggal_meninggal')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat Kematian -->
                            <div class="space-y-2">
                                <label for="tempat_kematian" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-hospital text-orange-500 w-4"></i>
                                    <span>Tempat Kematian</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="tempat_kematian"
                                    name="tempat_kematian" 
                                    value="{{ old('tempat_kematian') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Rumah Sakit / Rumah"
                                    required
                                >
                                @error('tempat_kematian')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kecamatan -->
                            <div class="space-y-2">
                                <label for="kecamatan" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-map-marker text-blue-500 w-4"></i>
                                    <span>Kecamatan</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="kecamatan"
                                    name="kecamatan" 
                                    value="{{ old('kecamatan') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Karanganyar"
                                    required
                                >
                                @error('kecamatan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kabupaten -->
                            <div class="space-y-2">
                                <label for="kabupaten" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-city text-green-500 w-4"></i>
                                    <span>Kabupaten</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="kabupaten"
                                    name="kabupaten" 
                                    value="{{ old('kabupaten') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Karanganyar"
                                    required
                                >
                                @error('kabupaten')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Provinsi -->
                            <div class="space-y-2">
                                <label for="provinsi" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-globe text-purple-500 w-4"></i>
                                    <span>Provinsi</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="provinsi"
                                    name="provinsi" 
                                    value="{{ old('provinsi') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Jawa Tengah"
                                    required
                                >
                                @error('provinsi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sebab Kematian -->
                            <div class="space-y-2">
                                <label for="sebab_kematian" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-notes-medical text-red-500 w-4"></i>
                                    <span>Sebab Kematian</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="sebab_kematian"
                                    name="sebab_kematian" 
                                    value="{{ old('sebab_kematian') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Sakit / Kecelakaan / Usia Lanjut"
                                    required
                                >
                                @error('sebab_kematian')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
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
                                class="flex-1 sm:flex-initial bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg hover:shadow-xl"
                            >
                                <span x-show="!isSubmitting" class="flex items-center justify-center space-x-2">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Ajukan Surat Keterangan Kematian</span>
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
                    const form = document.getElementById('kematian-form');
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
                confirmButtonColor: '#dc2626'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc2626'
            });
        @endif
    </script>
@endsection
