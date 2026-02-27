@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'KK', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-100 rounded-t-2xl p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-white/20 backdrop-blur-sm"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-600 rounded-full shadow-lg mb-4">
                    <i class="fas fa-users text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Keterangan Kartu Keluarga</h1>
                <p class="text-lg text-gray-600">Silakan isi formulir berikut dengan data yang valid dan lengkap</p>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-b-2xl shadow-xl border-t-4 border-indigo-500">
            <div class="p-8">
                <form 
                    id="kk-form"
                    method="POST" 
                    action="{{ route('surat.kk.submit') }}" 
                    enctype="multipart/form-data"
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
                                <span>Data Pemohon</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi pribadi pemohon surat keterangan</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                    value="{{ Auth::user()->name ?? old('nama_lengkap') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed transition duration-200 placeholder-gray-400"
                                    placeholder="Nama otomatis dari profil"
                                    readonly
                                >
                                @error('nama_lengkap')
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 placeholder-gray-400"
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
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                @error('tanggal_lahir')
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
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
                        </div>

                        <!-- Status and Occupation -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                                    required
                                >
                                    <option value="">Pilih Status</option>
                                    <option value="Kawin" {{ old('status_perkawinan') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                    <option value="Belum Kawin" {{ old('status_perkawinan') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                    <option value="Cerai" {{ old('status_perkawinan') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                                    <option value="Mati" {{ old('status_perkawinan') == 'Mati' ? 'selected' : '' }}>Mati</option>
                                    <option value="Cerai Hidup" {{ old('status_perkawinan') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                </select>
                                @error('status_perkawinan')
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                    placeholder="Contoh: Pegawai Swasta / Wiraswasta"
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 placeholder-gray-400 resize-none"
                                placeholder="Contoh: Jl. Raya No. 123, RT 01/RW 02, Kelurahan..."
                                required
                            >{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                                value="{{ old('keperluan') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                placeholder="Untuk keperluan apa surat ini?"
                                required
                            >
                            @error('keperluan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-cloud-upload-alt text-blue-500"></i>
                                <span>Upload Dokumen Pendukung</span>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Upload dokumen yang diperlukan untuk verifikasi</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium mb-2">Dokumen yang diperlukan:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Scan KTP Pemohon (wajib)</li>
                                        <li>Scan Kartu Keluarga lama atau hilang (jika ada)</li>
                                        <li>Dokumen pendukung lainnya</li>
                                    </ul>
                                    <p class="mt-2"><strong>Format:</strong> PDF, JPG, PNG | <strong>Ukuran maksimal:</strong> 2MB per file</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Upload KTP -->
                            <div class="space-y-2">
                                <label for="file_ktp" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-id-card text-blue-500 w-4"></i>
                                    <span>Upload KTP</span>
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="file" 
                                        id="file_ktp"
                                        name="file_ktp" 
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        onchange="updateFileName(this, 'file-ktp-name')"
                                        required
                                    >
                                    <div id="file-ktp-name" class="text-xs text-gray-500 mt-1">Belum ada file dipilih</div>
                                </div>
                                @error('file_ktp')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload KK Lama -->
                            <div class="space-y-2">
                                <label for="file_kk_lama" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-users text-purple-500 w-4"></i>
                                    <span>Upload KK Lama/Hilang</span>
                                    <span class="text-gray-500 text-xs">(Opsional)</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="file" 
                                        id="file_kk_lama"
                                        name="file_kk_lama" 
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                                        onchange="updateFileName(this, 'file-kk-name')"
                                    >
                                    <div id="file-kk-name" class="text-xs text-gray-500 mt-1">Belum ada file dipilih</div>
                                </div>
                                @error('file_kk_lama')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Dokumen Tambahan -->
                            <div class="space-y-2 md:col-span-2">
                                <label for="file_pendukung" class="block text-sm font-medium text-black flex items-center space-x-2">
                                    <i class="fas fa-paperclip text-green-500 w-4"></i>
                                    <span>Upload Dokumen Pendukung</span>
                                    <span class="text-gray-500 text-xs">(Opsional)</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="file" 
                                        id="file_pendukung"
                                        name="file_pendukung[]" 
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        multiple
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                                        onchange="updateMultipleFileName(this, 'file-pendukung-name')"
                                    >
                                    <div id="file-pendukung-name" class="text-xs text-gray-500 mt-1">Belum ada file dipilih (bisa pilih multiple files)</div>
                                </div>
                                @error('file_pendukung')
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
                                class="flex-1 sm:flex-initial bg-gradient-to-r from-indigo-500 to-blue-500 hover:from-indigo-600 hover:to-blue-600 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg hover:shadow-xl"
                            >
                                <span x-show="!isSubmitting" class="flex items-center justify-center space-x-2">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Ajukan Surat Keterangan KK</span>
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
        // Function to update file name display
        function updateFileName(input, displayId) {
            const display = document.getElementById(displayId);
            if (input.files.length > 0) {
                const file = input.files[0];
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    display.innerHTML = '<span class="text-red-500">File terlalu besar! Maksimal 2MB</span>';
                    input.value = ''; // Clear the input
                    return;
                }
                display.innerHTML = `<span class="text-green-600">✓ ${file.name} (${fileSize}MB)</span>`;
            } else {
                display.innerHTML = 'Belum ada file dipilih';
            }
        }

        // Function to update multiple file names display
        function updateMultipleFileName(input, displayId) {
            const display = document.getElementById(displayId);
            if (input.files.length > 0) {
                let fileList = [];
                let hasError = false;
                
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                    
                    if (file.size > 2 * 1024 * 1024) { // 2MB limit
                        hasError = true;
                        fileList.push(`<span class="text-red-500">✗ ${file.name} (${fileSize}MB) - Terlalu besar!</span>`);
                    } else {
                        fileList.push(`<span class="text-green-600">✓ ${file.name} (${fileSize}MB)</span>`);
                    }
                }
                
                if (hasError) {
                    display.innerHTML = '<div class="space-y-1">' + fileList.join('<br>') + '<br><span class="text-red-500 font-medium">Beberapa file melebihi batas 2MB!</span></div>';
                } else {
                    display.innerHTML = '<div class="space-y-1">' + fileList.join('<br>') + '</div>';
                }
            } else {
                display.innerHTML = 'Belum ada file dipilih (bisa pilih multiple files)';
            }
        }

        function submitForm() {
            return {
                isSubmitting: false,
                
                submitForm() {
                    if (this.isSubmitting) return;
                    
                    // Validate required fields
                    const form = document.getElementById('kk-form');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }
                    
                    // Validate file sizes
                    const fileInputs = form.querySelectorAll('input[type="file"]');
                    let hasOversizedFile = false;
                    
                    fileInputs.forEach(input => {
                        if (input.files.length > 0) {
                            for (let i = 0; i < input.files.length; i++) {
                                if (input.files[i].size > 2 * 1024 * 1024) { // 2MB
                                    hasOversizedFile = true;
                                    break;
                                }
                            }
                        }
                    });
                    
                    if (hasOversizedFile) {
                        Swal.fire({
                            title: 'File Terlalu Besar!',
                            text: 'Beberapa file melebihi batas maksimal 2MB. Silakan pilih file yang lebih kecil.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#6366f1'
                        });
                        return;
                    }
                    
                    this.isSubmitting = true;
                    
                    // Show loading message
                    Swal.fire({
                        title: 'Mengupload File...',
                        text: 'Mohon tunggu, file sedang diupload dan data sedang diproses.',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
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
                confirmButtonColor: '#6366f1'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                title: 'Gagal!',
                html: `<ul style='text-align:left;'>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6366f1'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6366f1'
            });
        @endif

        // Initialize file input styling on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial state for file displays
            updateFileName(document.getElementById('file_ktp'), 'file-ktp-name');
            updateFileName(document.getElementById('file_kk_lama'), 'file-kk-name');
            updateMultipleFileName(document.getElementById('file_pendukung'), 'file-pendukung-name');
        });
    </script>
@endsection
