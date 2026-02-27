@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Statistik', 'url' => route('admin.statistik.index')],
    ['label' => 'Data Kelompok Umur', 'url' => route('admin.statistik.umur')],
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
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Tambah Data Kelompok Umur</h1>
                    <p class="text-gray-600 text-center md:text-left">Tambahkan data statistik kelompok umur penduduk baru</p>
                </div>
                @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                
                <div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Tambah Data Kelompok Umur</h1>
                <p class="text-gray-600">Tambahkan data statistik kelompok umur penduduk baru</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.statistik.umur') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.statistik.umur.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Kelompok Umur -->
            <div>
                <label for="kelompok_umur" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-birthday-cake text-green-600 mr-1"></i>
                    Nama Kelompok Umur *
                </label>
                <input type="text" 
                       name="kelompok_umur" 
                       id="kelompok_umur"
                       value="{{ old('kelompok_umur') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('kelompok_umur') border-red-500 @enderror"
                       placeholder="Contoh: 0-1 Tahun"
                       required>
                @error('kelompok_umur')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rentang Usia -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Usia Minimum -->
                <div>
                    <label for="usia_min" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort-numeric-down text-blue-600 mr-1"></i>
                        Usia Minimum *
                    </label>
                    <input type="number" 
                           name="usia_min" 
                           id="usia_min"
                           value="{{ old('usia_min', 0) }}"
                           min="0"
                           max="150"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('usia_min') border-red-500 @enderror"
                           onchange="updateKelompokName()"
                           required>
                    @error('usia_min')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Usia Maksimum -->
                <div>
                    <label for="usia_max" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort-numeric-up text-blue-600 mr-1"></i>
                        Usia Maksimum (Opsional)
                    </label>
                    <input type="number" 
                           name="usia_max" 
                           id="usia_max"
                           value="{{ old('usia_max') }}"
                           min="0"
                           max="150"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('usia_max') border-red-500 @enderror"
                           onchange="updateKelompokName()"
                           placeholder="Kosongkan untuk umur terbuka (contoh: 80+)">
                    @error('usia_max')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-600">Kosongkan jika untuk rentang umur terbuka (contoh: 80+ tahun)</p>
                </div>
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('laki_laki') border-red-500 @enderror"
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('perempuan') border-red-500 @enderror"
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
                    <i class="fas fa-calculator text-purple-600 mr-1"></i>
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                          placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.statistik.umur') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>

    <!-- Information Panel -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-green-900 mb-2">Tips Pengisian</h3>
                <div class="text-green-800 space-y-2">
                    <p>• Nama kelompok umur akan otomatis terisi berdasarkan rentang usia</p>
                    <p>• Usia maksimum boleh dikosongkan untuk rentang terbuka (contoh: 80+ tahun)</p>
                    <p>• Pastikan rentang tidak tumpang tindih dengan data yang sudah ada</p>
                    <p>• Data akan diurutkan otomatis berdasarkan usia minimum</p>
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

function updateKelompokName() {
    const usiaMin = document.getElementById('usia_min').value;
    const usiaMax = document.getElementById('usia_max').value;
    const kelompokInput = document.getElementById('kelompok_umur');
    
    if (usiaMin) {
        let kelompokName = '';
        if (usiaMax && usiaMax > usiaMin) {
            kelompokName = `${usiaMin}-${usiaMax} Tahun`;
        } else if (usiaMax === '' || !usiaMax) {
            kelompokName = `${usiaMin}+ Tahun`;
        } else {
            kelompokName = `${usiaMin} Tahun`;
        }
        
        if (kelompokInput.value === '' || kelompokInput.value.includes('Tahun')) {
            kelompokInput.value = kelompokName;
        }
    }
}

// Calculate initial total on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
    updateKelompokName();
});
</script>
@endsection
