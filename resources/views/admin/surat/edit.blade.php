@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Pengelolaan Surat', 'url' => url('/admin/surat')],
    ['label' => 'Edit Surat', 'url' => '#'],
];
@endphp

    @include('admin.partials.alpinejs')
    @include('admin.partials.navbar')
    <div id="adminLayout" x-data="{ sidebarMinimized: false }" class="flex min-h-screen bg-gray-50">
        @include('admin.partials.sidebar')
        <main id="adminMain" 
              class="flex-1 pt-24 pb-8 px-2 sm:px-4 md:px-8 transition-all duration-300"
              :class="{
                  'ml-16': $store.sidebar && $store.sidebar.isOpen && sidebarMinimized,
                  'ml-64': $store.sidebar && $store.sidebar.isOpen && !sidebarMinimized,
                  'ml-0': !$store.sidebar || !$store.sidebar.isOpen
              }"
              x-init="
                  $watch('$store.sidebar.isOpen', value => {
                      if (!value) sidebarMinimized = false;
                  });
                  // Listen for minimize state changes from sidebar
                  document.addEventListener('sidebar-minimized', (e) => {
                      sidebarMinimized = e.detail.minimized;
                  });
              "
        >
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Edit Surat Keterangan</h1>
                    <p class="text-gray-600 text-center md:text-left">Edit data surat keterangan {{ ucfirst($jenis) }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.surat.update', $surat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="jenis_surat" value="{{ $jenis }}">

                        @if($jenis === 'domisili')
                            <!-- Form fields for Domisili -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="nama" id="nama" 
                                           value="{{ old('nama', $surat->nama) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('nama') border-red-500 @enderror" 
                                           required>
                                    @error('nama')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                    <input type="text" name="nik" id="nik" 
                                           value="{{ old('nik', $surat->nik) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('nik') border-red-500 @enderror" 
                                           required>
                                    @error('nik')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" 
                                           value="{{ old('tempat_lahir', $surat->tempat_lahir) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('tempat_lahir') border-red-500 @enderror" 
                                           required>
                                    @error('tempat_lahir')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                                           value="{{ old('tanggal_lahir', $surat->tanggal_lahir) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('tanggal_lahir') border-red-500 @enderror" 
                                           required>
                                    @error('tanggal_lahir')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('jenis_kelamin') border-red-500 @enderror" 
                                            required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $surat->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $surat->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="agama" class="block text-sm font-medium text-gray-700 mb-2">Agama</label>
                                    <select name="agama" id="agama" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('agama') border-red-500 @enderror" 
                                            required>
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" {{ old('agama', $surat->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ old('agama', $surat->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ old('agama', $surat->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ old('agama', $surat->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ old('agama', $surat->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ old('agama', $surat->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                    @error('agama')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status_perkawinan" class="block text-sm font-medium text-gray-700 mb-2">Status Perkawinan</label>
                                    <select name="status_perkawinan" id="status_perkawinan" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('status_perkawinan') border-red-500 @enderror" 
                                            required>
                                        <option value="">Pilih Status</option>
                                        <option value="Belum Kawin" {{ old('status_perkawinan', $surat->status_perkawinan) == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                        <option value="Kawin" {{ old('status_perkawinan', $surat->status_perkawinan) == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                        <option value="Cerai Hidup" {{ old('status_perkawinan', $surat->status_perkawinan) == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                        <option value="Cerai Mati" {{ old('status_perkawinan', $surat->status_perkawinan) == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                    </select>
                                    @error('status_perkawinan')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                    <input type="text" name="pekerjaan" id="pekerjaan" 
                                           value="{{ old('pekerjaan', $surat->pekerjaan) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('pekerjaan') border-red-500 @enderror" 
                                           required>
                                    @error('pekerjaan')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('alamat') border-red-500 @enderror" 
                                          required>{{ old('alamat', $surat->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                                <textarea name="keperluan" id="keperluan" rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('keperluan') border-red-500 @enderror" 
                                          required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                @error('keperluan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        @elseif($jenis === 'ktp')
                            <!-- Form fields for KTP - similar structure -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" 
                                           value="{{ old('nama_lengkap', $surat->nama_lengkap) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('nama_lengkap') border-red-500 @enderror" 
                                           required>
                                    @error('nama_lengkap')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                    <input type="text" name="nik" id="nik" 
                                           value="{{ old('nik', $surat->nik) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('nik') border-red-500 @enderror" 
                                           required>
                                    @error('nik')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Add other KTP fields similar to domisili -->
                                <div>
                                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" 
                                           value="{{ old('tempat_lahir', $surat->tempat_lahir) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent" 
                                           required>
                                </div>

                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                                           value="{{ old('tanggal_lahir', $surat->tanggal_lahir) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent" 
                                           required>
                                </div>

                                <div>
                                    <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                                    <textarea name="keperluan" id="keperluan" rows="3" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent" 
                                              required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                </div>
                            </div>

                        @else
                            <!-- Generic form for other types -->
                            <div class="mb-6">
                                <p class="text-gray-600">Form edit untuk jenis surat "{{ $jenis }}" sedang dalam pengembangan.</p>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" class="bg-[#0088cc] text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                            <a href="/admin/surat" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-semibold text-center">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
