@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Statistik', 'url' => route('admin.statistik.index')],
    ['label' => 'Data Pendidikan', 'url' => route('admin.statistik.pendidikan')],
    ['label' => 'Tambah Data', 'url' => '#'],
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
                  document.addEventListener('sidebar-minimized', (e) => {
                      sidebarMinimized = e.detail.minimized;
                  });
              "
        >
            
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Tambah Data Tingkat Pendidikan</h1>
                    <p class="text-gray-600 text-center md:text-left">Tambahkan data statistik tingkat pendidikan penduduk baru</p>
                </div>
                @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                
<div class="space-y-6">
    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Form Tambah Data</h2>
                <p class="text-gray-600">Lengkapi form di bawah ini untuk menambahkan data pendidikan</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.statistik.pendidikan') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
        <form action="{{ route('admin.statistik.pendidikan.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Tingkat Pendidikan -->
            <div>
                <label for="tingkat_pendidikan" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-graduation-cap text-purple-600 mr-1"></i>
                    Tingkat Pendidikan *
                </label>
                <input type="text" 
                       name="tingkat_pendidikan" 
                       id="tingkat_pendidikan"
                       value="{{ old('tingkat_pendidikan') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tingkat_pendidikan') border-red-500 @enderror"
                       placeholder="Contoh: Tamat SD/Sederajat"
                       required>
                @error('tingkat_pendidikan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Urutan -->
            <div>
                <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sort-numeric-down text-blue-600 mr-1"></i>
                    Urutan *
                </label>
                <input type="number" 
                       name="urutan" 
                       id="urutan"
                       value="{{ old('urutan', 1) }}"
                       min="1"
                       max="100"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('urutan') border-red-500 @enderror"
                       required>
                @error('urutan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-600">Urutan menentukan posisi data di tabel (semakin kecil, semakin atas)</p>
            </div>

            <!-- Data Penduduk -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Laki-laki -->
                <div>
                    <label for="laki_laki" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-male text-blue-600 mr-1"></i>
                        Jumlah Laki-laki *
                    </label>
                    <input type="number" 
                           name="laki_laki" 
                           id="laki_laki"
                           value="{{ old('laki_laki', 0) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('laki_laki') border-red-500 @enderror"
                           onchange="calculateTotal()"
                           required>
                    @error('laki_laki')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Perempuan -->
                <div>
                    <label for="perempuan" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-female text-pink-600 mr-1"></i>
                        Jumlah Perempuan *
                    </label>
                    <input type="number" 
                           name="perempuan" 
                           id="perempuan"
                           value="{{ old('perempuan', 0) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('perempuan') border-red-500 @enderror"
                           onchange="calculateTotal()"
                           required>
                    @error('perempuan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Total (Auto Calculate) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calculator text-green-600 mr-1"></i>
                    Total Keseluruhan
                </label>
                <div class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-lg font-semibold text-center">
                    <span id="total_display">0</span> orang
                </div>
                <p class="mt-1 text-sm text-gray-600">Total dihitung otomatis dari jumlah laki-laki + perempuan</p>
            </div>

            <!-- Keterangan -->
            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note text-yellow-600 mr-1"></i>
                    Keterangan (Opsional)
                </label>
                <textarea name="keterangan" 
                          id="keterangan"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                          placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.statistik.pendidikan') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>

    <!-- Information Panel -->
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-purple-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-purple-900 mb-2">Tips Pengisian</h3>
                <div class="text-purple-800 space-y-2">
                    <p>• Gunakan nama tingkat pendidikan yang sesuai standar nasional</p>
                    <p>• Urutan menentukan posisi data di tabel dan grafik</p>
                    <p>• Pastikan data akurat sesuai dengan kondisi terkini penduduk</p>
                    <p>• Total akan dihitung otomatis saat Anda mengisi jumlah laki-laki dan perempuan</p>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </main>
    </div>

<script>
function calculateTotal() {
    const lakiLaki = parseInt(document.getElementById('laki_laki').value) || 0;
    const perempuan = parseInt(document.getElementById('perempuan').value) || 0;
    const total = lakiLaki + perempuan;
    
    document.getElementById('total_display').textContent = total.toLocaleString('id-ID');
}

// Calculate initial total on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
@endsection
