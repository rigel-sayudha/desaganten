@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Pelayanan Surat', 'url' => url('/surat/form')],
    ['label' => 'Surat Keterangan Usaha', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])

<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-yellow-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-full shadow-lg mb-6">
                <i class="fas fa-store text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Surat Keterangan Usaha</h1>
            <p class="text-gray-600 max-w-lg mx-auto">
                Formulir untuk mengajukan surat keterangan usaha atau kegiatan ekonomi
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-2xl shadow-xl border border-white border-opacity-50 overflow-hidden">
            
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-orange-500 to-yellow-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>
                    Formulir Pengajuan Surat Keterangan Usaha
                </h2>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-6 mt-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mt-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Form Content -->
            <form action="{{ route('surat.usaha.submit') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Data Pribadi Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user text-orange-600 mr-2"></i>
                        Data Pribadi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" 
                                   value="{{ old('nama_lengkap', 'Sugito') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('nama_lengkap') border-red-500 @enderror" 
                                   required>
                            @error('nama_lengkap')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                                NIK <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nik" id="nik" 
                                   value="{{ old('nik') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('nik') border-red-500 @enderror" 
                                   maxlength="16" required>
                            @error('nik')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" 
                                   value="{{ old('tempat_lahir') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('tempat_lahir') border-red-500 @enderror" 
                                   required>
                            @error('tempat_lahir')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('tanggal_lahir') border-red-500 @enderror" 
                                   required>
                            @error('tanggal_lahir')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin" id="jenis_kelamin" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('jenis_kelamin') border-red-500 @enderror" 
                                    required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="agama" class="block text-sm font-medium text-gray-700 mb-2">
                                Agama <span class="text-red-500">*</span>
                            </label>
                            <select name="agama" id="agama" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('agama') border-red-500 @enderror" 
                                    required>
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

                        <div>
                            <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                                Pekerjaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pekerjaan" id="pekerjaan" 
                                   value="{{ old('pekerjaan') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('pekerjaan') border-red-500 @enderror" 
                                   required>
                            @error('pekerjaan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status_perkawinan" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Perkawinan <span class="text-red-500">*</span>
                            </label>
                            <select name="status_perkawinan" id="status_perkawinan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('status_perkawinan') border-red-500 @enderror" 
                                    required>
                                <option value="">Pilih Status</option>
                                <option value="Belum Kawin" {{ old('status_perkawinan') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                <option value="Kawin" {{ old('status_perkawinan') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                <option value="Cerai Hidup" {{ old('status_perkawinan') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                <option value="Cerai Mati" {{ old('status_perkawinan') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                            </select>
                            @error('status_perkawinan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" id="alamat" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('alamat') border-red-500 @enderror" 
                                  required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Data Usaha Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-store text-orange-600 mr-2"></i>
                        Data Usaha
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_usaha" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Usaha <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_usaha" id="nama_usaha" 
                                   value="{{ old('nama_usaha') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('nama_usaha') border-red-500 @enderror" 
                                   placeholder="Contoh: Warung Makan Pak Budi"
                                   required>
                            @error('nama_usaha')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_usaha" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Usaha <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="jenis_usaha" id="jenis_usaha" 
                                   value="{{ old('jenis_usaha') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('jenis_usaha') border-red-500 @enderror" 
                                   placeholder="Contoh: Kuliner, Retail, Jasa, dll"
                                   required>
                            @error('jenis_usaha')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lama_usaha" class="block text-sm font-medium text-gray-700 mb-2">
                                Lama Usaha <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="lama_usaha" id="lama_usaha" 
                                   value="{{ old('lama_usaha') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('lama_usaha') border-red-500 @enderror" 
                                   placeholder="Contoh: 2 tahun, 6 bulan"
                                   required>
                            @error('lama_usaha')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="modal_usaha" class="block text-sm font-medium text-gray-700 mb-2">
                                Modal Usaha <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="modal_usaha" id="modal_usaha" 
                                   value="{{ old('modal_usaha') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('modal_usaha') border-red-500 @enderror" 
                                   placeholder="Contoh: Rp 10.000.000"
                                   required>
                            @error('modal_usaha')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="omzet_usaha" class="block text-sm font-medium text-gray-700 mb-2">
                                Omzet Usaha per Bulan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="omzet_usaha" id="omzet_usaha" 
                                   value="{{ old('omzet_usaha') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('omzet_usaha') border-red-500 @enderror" 
                                   placeholder="Contoh: Rp 5.000.000"
                                   required>
                            @error('omzet_usaha')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="alamat_usaha" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Usaha <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat_usaha" id="alamat_usaha" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('alamat_usaha') border-red-500 @enderror" 
                                  placeholder="Masukkan alamat lengkap tempat usaha"
                                  required>{{ old('alamat_usaha') }}</textarea>
                        @error('alamat_usaha')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Keperluan Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-clipboard-list text-orange-600 mr-2"></i>
                        Keperluan Surat
                    </h3>
                    
                    <div>
                        <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keperluan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="keperluan" id="keperluan" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('keperluan') border-red-500 @enderror" 
                                  placeholder="Jelaskan keperluan surat keterangan usaha ini..."
                                  required>{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Document Upload Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cloud-upload-alt text-orange-600 mr-2"></i>
                        Upload Dokumen Pendukung
                    </h3>
                    
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-orange-500 mt-0.5"></i>
                            <div class="text-sm text-orange-700">
                                <p class="font-medium mb-2">Dokumen yang diperlukan:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Scan KTP Pemilik Usaha (wajib)</li>
                                    <li>Scan Kartu Keluarga</li>
                                    <li>Foto tempat usaha (wajib)</li>
                                    <li>Dokumen izin usaha (jika ada)</li>
                                    <li>Surat keterangan dari RT/RW</li>
                                </ul>
                                <p class="mt-2"><strong>Format:</strong> PDF, JPG, PNG | <strong>Ukuran maksimal:</strong> 2MB per file</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Upload KTP -->
                        <div class="space-y-2">
                            <label for="file_ktp" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-id-card text-blue-500 w-4"></i>
                                <span>Upload KTP Pemilik Usaha</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_ktp"
                                    name="file_ktp" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    required
                                >
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-blue-600">Klik untuk upload</span> atau drag file ke sini
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 2MB)</p>
                                </div>
                            </div>
                            @error('file_ktp')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload KK -->
                        <div class="space-y-2">
                            <label for="file_kk" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-users text-purple-500 w-4"></i>
                                <span>Upload Kartu Keluarga</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_kk"
                                    name="file_kk" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    required
                                >
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors duration-200">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-purple-600">Klik untuk upload</span> atau drag file ke sini
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 2MB)</p>
                                </div>
                            </div>
                            @error('file_kk')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Foto Tempat Usaha -->
                        <div class="space-y-2">
                            <label for="file_foto_usaha" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-camera text-green-500 w-4"></i>
                                <span>Upload Foto Tempat Usaha</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_foto_usaha"
                                    name="file_foto_usaha" 
                                    accept=".jpg,.jpeg,.png"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    required
                                >
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors duration-200">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-green-600">Klik untuk upload</span> atau drag file ke sini
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG (Max 2MB)</p>
                                </div>
                            </div>
                            @error('file_foto_usaha')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Izin Usaha -->
                        <div class="space-y-2">
                            <label for="file_izin_usaha" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-file-contract text-yellow-500 w-4"></i>
                                <span>Upload Izin Usaha</span>
                                <span class="text-gray-500 text-xs">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_izin_usaha"
                                    name="file_izin_usaha" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                >
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-yellow-400 transition-colors duration-200">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-yellow-600">Klik untuk upload</span> atau drag file ke sini
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 2MB)</p>
                                </div>
                            </div>
                            @error('file_izin_usaha')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Surat RT/RW -->
                        <div class="space-y-2 md:col-span-2">
                            <label for="file_pengantar" class="block text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-file-alt text-orange-500 w-4"></i>
                                <span>Upload Surat RT/RW</span>
                                <span class="text-gray-500 text-xs">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    id="file_pengantar"
                                    name="file_pengantar" 
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                >
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-400 transition-colors duration-200">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-orange-600">Klik untuk upload</span> atau drag file ke sini
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max 2MB)</p>
                                </div>
                            </div>
                            @error('file_pengantar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-orange-500 to-yellow-600 text-white rounded-lg hover:from-orange-600 hover:to-yellow-700 transition-all duration-200 font-medium flex items-center justify-center space-x-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-paper-plane"></i>
                            <span>Ajukan Surat</span>
                        </button>
                        
                        <button type="button" 
                                onclick="history.back()"
                                class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium flex items-center justify-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Information Card -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h4 class="font-semibold text-blue-900 mb-3 flex items-center space-x-2">
                <i class="fas fa-info-circle text-blue-600"></i>
                <span>Informasi Penting</span>
            </h4>
            <div class="text-blue-800 text-sm space-y-2">
                <p class="flex items-start space-x-2">
                    <i class="fas fa-check text-blue-600 w-4 mt-0.5"></i>
                    <span>Pastikan semua data yang diisi sudah benar dan sesuai dengan dokumen yang dimiliki</span>
                </p>
                <p class="flex items-start space-x-2">
                    <i class="fas fa-check text-blue-600 w-4 mt-0.5"></i>
                    <span>Surat keterangan usaha akan diproses dalam 3-5 hari kerja</span>
                </p>
                <p class="flex items-start space-x-2">
                    <i class="fas fa-check text-blue-600 w-4 mt-0.5"></i>
                    <span>Hubungi kantor desa untuk informasi lebih lanjut</span>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Format NIK - hanya angka, maksimal 16 karakter
document.getElementById('nik').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 16) {
        value = value.slice(0, 16);
    }
    e.target.value = value;
});
</script>
@endpush
@endsection
