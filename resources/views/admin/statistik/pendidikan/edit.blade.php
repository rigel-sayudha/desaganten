@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Statistik', 'url' => route('admin.statistik.index')],
    ['label' => 'Data Pendidikan', 'url' => route('admin.statistik.pendidikan')],
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
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Edit Data Tingkat Pendidikan</h1>
                    <p class="text-gray-600 text-center md:text-left">Ubah data statistik tingkat pendidikan {{ $pendidikan->tingkat_pendidikan }}</p>
                </div>
                @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                
<div class="space-y-6">
    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Form Edit Data</h2>
                <p class="text-gray-600">Ubah data statistik tingkat pendidikan</p>
            </div>
            <div class="mt-4 md:mt-0 space-x-2">
                <a href="{{ route('admin.statistik.pendidikan') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
        <form action="{{ route('admin.statistik.pendidikan.update', $pendidikan->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Tingkat Pendidikan -->
            <div>
                <label for="tingkat_pendidikan" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-graduation-cap text-purple-600 mr-1"></i>
                    Tingkat Pendidikan *
                </label>
                <input type="text" 
                       name="tingkat_pendidikan" 
                       id="tingkat_pendidikan"
                       value="{{ old('tingkat_pendidikan', $pendidikan->tingkat_pendidikan) }}"
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
                       value="{{ old('urutan', $pendidikan->urutan) }}"
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
                           value="{{ old('laki_laki', $pendidikan->laki_laki) }}"
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
                           value="{{ old('perempuan', $pendidikan->perempuan) }}"
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
                    <span id="total_display">{{ number_format($pendidikan->jumlah, 0, ',', '.') }}</span> orang
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
                          placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan', $pendidikan->keterangan) }}</textarea>
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>


    <!-- Delete Section -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-lg font-medium text-red-900 mb-2">Zona Berbahaya</h3>
                <p class="text-red-800 mb-4">Tindakan ini akan menghapus data secara permanen dan tidak dapat dikembalikan.</p>
                <button type="button" 
                        onclick="confirmDelete()"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Data Ini
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 m-4 max-w-md">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
        </div>
        <p class="text-gray-600 mb-6">
            Apakah Anda yakin ingin menghapus data tingkat pendidikan "<strong>{{ $pendidikan->tingkat_pendidikan }}</strong>"? 
            Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex justify-end space-x-3">
            <button type="button" 
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Batal
            </button>
            <form action="{{ route('admin.statistik.pendidikan.destroy', $pendidikan->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function calculateTotal() {
    const lakiLaki = parseInt(document.getElementById('laki_laki').value) || 0;
    const perempuan = parseInt(document.getElementById('perempuan').value) || 0;
    const total = lakiLaki + perempuan;
    
    document.getElementById('total_display').textContent = total.toLocaleString('id-ID');
}

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

// Calculate initial total on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
            </div>
        </main>
    </div>
@endsection
