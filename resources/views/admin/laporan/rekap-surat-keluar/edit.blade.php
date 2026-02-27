@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Laporan', 'url' => '#'],
    ['label' => 'Data Rekap Surat Keluar', 'url' => route('admin.laporan.rekap-surat-keluar.index')],
    ['label' => 'Edit Data', 'url' => '#'],
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
        >
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Data Rekap Surat Keluar</h1>
        <a href="{{ route('admin.laporan.rekap-surat-keluar.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.laporan.rekap-surat-keluar.update', $rekapSurat->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tanggal Surat -->
            <div>
                <label for="tanggal_surat" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Surat <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       id="tanggal_surat" 
                       name="tanggal_surat" 
                       value="{{ old('tanggal_surat', $rekapSurat->tanggal_surat->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_surat') border-red-500 @enderror"
                       required>
                @error('tanggal_surat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor Surat -->
            <div>
                <label for="nomor_surat" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Surat
                </label>
                <input type="text" 
                       id="nomor_surat" 
                       name="nomor_surat" 
                       value="{{ old('nomor_surat', $rekapSurat->nomor_surat) }}"
                       placeholder="Masukkan nomor surat"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nomor_surat') border-red-500 @enderror">
                @error('nomor_surat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Pemohon -->
            <div>
                <label for="nama_pemohon" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Pemohon <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nama_pemohon" 
                       name="nama_pemohon" 
                       value="{{ old('nama_pemohon', $rekapSurat->nama_pemohon) }}"
                       placeholder="Masukkan nama pemohon"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_pemohon') border-red-500 @enderror"
                       required>
                @error('nama_pemohon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Surat -->
            <div>
                <label for="jenis_surat" class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Surat <span class="text-red-500">*</span>
                </label>
                <select id="jenis_surat" 
                        name="jenis_surat" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis_surat') border-red-500 @enderror"
                        required>
                    <option value="">Pilih Jenis Surat</option>
                    <option value="Surat Keterangan Domisili" {{ old('jenis_surat', $rekapSurat->jenis_surat) == 'Surat Keterangan Domisili' ? 'selected' : '' }}>
                        Surat Keterangan Domisili
                    </option>
                    <option value="Surat Keterangan Tidak Mampu" {{ old('jenis_surat', $rekapSurat->jenis_surat) == 'Surat Keterangan Tidak Mampu' ? 'selected' : '' }}>
                        Surat Keterangan Tidak Mampu
                    </option>
                    <option value="Surat Keterangan Usaha" {{ old('jenis_surat', $rekapSurat->jenis_surat) == 'Surat Keterangan Usaha' ? 'selected' : '' }}>
                        Surat Keterangan Usaha
                    </option>
                    <option value="Surat Pengantar" {{ old('jenis_surat', $rekapSurat->jenis_surat) == 'Surat Pengantar' ? 'selected' : '' }}>
                        Surat Pengantar
                    </option>
                    <option value="Lainnya" {{ old('jenis_surat', $rekapSurat->jenis_surat) == 'Lainnya' ? 'selected' : '' }}>
                        Lainnya
                    </option>
                </select>
                @error('jenis_surat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status" 
                        name="status" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                        required>
                    <option value="">Pilih Status</option>
                    <option value="pending" {{ old('status', $rekapSurat->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ old('status', $rekapSurat->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ old('status', $rekapSurat->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ old('status', $rekapSurat->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Untuk Keperluan -->
            <div class="md:col-span-2">
                <label for="untuk_keperluan" class="block text-sm font-medium text-gray-700 mb-2">
                    Untuk Keperluan <span class="text-red-500">*</span>
                </label>
                <textarea id="untuk_keperluan" 
                          name="untuk_keperluan" 
                          rows="3"
                          placeholder="Masukkan keperluan surat"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('untuk_keperluan') border-red-500 @enderror"
                          required>{{ old('untuk_keperluan', $rekapSurat->untuk_keperluan) }}</textarea>
                @error('untuk_keperluan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan
                </label>
                <textarea id="keterangan" 
                          name="keterangan" 
                          rows="3"
                          placeholder="Masukkan keterangan tambahan (opsional)"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $rekapSurat->keterangan) }}</textarea>
                @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Info Tambahan -->
        @if($rekapSurat->surat_type && $rekapSurat->surat_id)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Informasi Data Terkait</h3>
            <p class="text-sm text-blue-700">
                Data ini terhubung dengan: <strong>{{ $rekapSurat->surat_type }}</strong> ID: <strong>{{ $rekapSurat->surat_id }}</strong>
            </p>
        </div>
        @endif

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.laporan.rekap-surat-keluar.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg transition duration-200">
                Batal
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                Update Data
            </button>
        </div>
    </form>
</div>

        </main>
    </div>
@endsection
