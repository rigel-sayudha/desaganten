@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Statistik', 'url' => route('admin.statistik.index')],
    ['label' => 'Data Pekerjaan', 'url' => route('admin.statistik.pekerjaan')],
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
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Edit Data Pekerjaan</h1>
                    <p class="text-gray-600 text-center md:text-left">Ubah data statistik pekerjaan {{ $pekerjaan->nama_pekerjaan }}</p>
                </div>
                @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                
                <div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Data Pekerjaan</h1>
                <p class="text-gray-600">Edit data statistik pekerjaan: {{ $pekerjaan->nama_pekerjaan }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.statistik.pekerjaan') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.statistik.pekerjaan.update', $pekerjaan) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Nama Pekerjaan -->
            <div>
                <label for="nama_pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-briefcase text-blue-600 mr-1"></i>
                    Nama Pekerjaan *
                </label>
                <input type="text" 
                       name="nama_pekerjaan" 
                       id="nama_pekerjaan"
                       value="{{ old('nama_pekerjaan', $pekerjaan->nama_pekerjaan) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_pekerjaan') border-red-500 @enderror"
                       placeholder="Contoh: Petani/Pekebun"
                       required>
                @error('nama_pekerjaan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                           value="{{ old('laki_laki', $pekerjaan->laki_laki) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('laki_laki') border-red-500 @enderror"
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
                           value="{{ old('perempuan', $pekerjaan->perempuan) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('perempuan') border-red-500 @enderror"
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
                    <span id="total_display">{{ number_format($pekerjaan->jumlah, 0, ',', '.') }}</span> orang
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan', $pekerjaan->keterangan) }}</textarea>
                @error('keterangan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.statistik.pekerjaan') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Update Data
                </button>
            </div>
        </form>
    </div>

    <!-- Current Data Info -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-yellow-900 mb-2">Data Saat Ini</h3>
                <div class="text-yellow-800 space-y-1">
                    <p>• Nama: {{ $pekerjaan->nama_pekerjaan }}</p>
                    <p>• Laki-laki: {{ number_format($pekerjaan->laki_laki, 0, ',', '.') }} orang</p>
                    <p>• Perempuan: {{ number_format($pekerjaan->perempuan, 0, ',', '.') }} orang</p>
                    <p>• Total: {{ number_format($pekerjaan->jumlah, 0, ',', '.') }} orang</p>
                    <p>• Terakhir diupdate: {{ $pekerjaan->updated_at->format('d F Y H:i') }}</p>
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
