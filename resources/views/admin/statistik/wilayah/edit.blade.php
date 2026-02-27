@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Statistik', 'url' => route('admin.statistik.index')],
    ['label' => 'Data Wilayah', 'url' => route('admin.statistik.wilayah.index')],
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
            
            <!-- Flash Messages -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Edit Data Wilayah</h1>
                    <p class="text-gray-600 text-center md:text-left">Perbarui data statistik wilayah: {{ $wilayah->nama_wilayah }}</p>
                </div>
                @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                
                <div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Data Wilayah</h1>
                <p class="text-gray-600">Perbarui data statistik untuk: <strong>{{ $wilayah->nama_wilayah }}</strong></p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.statistik.wilayah.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Error Validation Display -->
    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center mb-2">
            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
            <h3 class="text-lg font-medium text-red-800">Terdapat kesalahan pada input:</h3>
        </div>
        <ul class="text-red-700 list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.statistik.wilayah.update', $wilayah) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Wilayah -->
            <div>
                <label for="nama_wilayah" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt text-orange-600 mr-1"></i>
                    Nama Wilayah <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_wilayah" 
                       id="nama_wilayah"
                       value="{{ old('nama_wilayah', $wilayah->nama_wilayah) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('nama_wilayah') border-red-500 @enderror"
                       placeholder="Contoh: RT 01, RW 02, Dusun Mawar, etc."
                       required>
                @error('nama_wilayah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Masukkan nama wilayah secara lengkap dan jelas</p>
            </div>

            <!-- Jenis Wilayah -->
            <div>
                <label for="jenis_wilayah" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tags text-orange-600 mr-1"></i>
                    Jenis Wilayah <span class="text-red-500">*</span>
                </label>
                <select name="jenis_wilayah" 
                        id="jenis_wilayah"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('jenis_wilayah') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Jenis Wilayah --</option>
                    <option value="Dusun" {{ old('jenis_wilayah', $wilayah->jenis_wilayah) == 'Dusun' ? 'selected' : '' }}>Dusun</option>
                    <option value="RT" {{ old('jenis_wilayah', $wilayah->jenis_wilayah) == 'RT' ? 'selected' : '' }}>RT (Rukun Tetangga)</option>
                    <option value="RW" {{ old('jenis_wilayah', $wilayah->jenis_wilayah) == 'RW' ? 'selected' : '' }}>RW (Rukun Warga)</option>
                    <option value="Kelompok" {{ old('jenis_wilayah', $wilayah->jenis_wilayah) == 'Kelompok' ? 'selected' : '' }}>Kelompok</option>
                    <option value="Lingkungan" {{ old('jenis_wilayah', $wilayah->jenis_wilayah) == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                    <option value="Kampung" {{ old('jenis_wilayah', $wilayah->jenis_wilayah) == 'Kampung' ? 'selected' : '' }}>Kampung</option>
                    <option value="Blok" {{ old('jenis_wilayah', $wilayah->jenis_wilayah) == 'Blok' ? 'selected' : '' }}>Blok</option>
                    <option value="Sektor" {{ old('jenis_wilayah', $wilayah->jenis_wilayah) == 'Sektor' ? 'selected' : '' }}>Sektor</option>
                </select>
                @error('jenis_wilayah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Pilih kategori wilayah yang sesuai</p>
            </div>

            <!-- Grid untuk Laki-laki dan Perempuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Laki-laki -->
                <div>
                    <label for="laki_laki" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-male text-blue-600 mr-1"></i>
                        Jumlah Laki-laki <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="laki_laki" 
                           id="laki_laki"
                           value="{{ old('laki_laki', $wilayah->laki_laki) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('laki_laki') border-red-500 @enderror"
                           placeholder="0"
                           required>
                    @error('laki_laki')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Perempuan -->
                <div>
                    <label for="perempuan" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-female text-pink-600 mr-1"></i>
                        Jumlah Perempuan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="perempuan" 
                           id="perempuan"
                           value="{{ old('perempuan', $wilayah->perempuan) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('perempuan') border-red-500 @enderror"
                           placeholder="0"
                           required>
                    @error('perempuan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Data Display -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Data Saat Ini:</h4>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Laki-laki:</span>
                        <span class="font-medium">{{ number_format($wilayah->laki_laki, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Perempuan:</span>
                        <span class="font-medium">{{ number_format($wilayah->perempuan, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Total:</span>
                        <span class="font-medium">{{ number_format($wilayah->jumlah, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Total Display (Read-only, calculated automatically) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calculator text-green-600 mr-1"></i>
                    Total Penduduk (Baru)
                </label>
                <div class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                    <span id="total_display">{{ $wilayah->jumlah }}</span>
                    <span class="text-sm text-gray-500 ml-2">(Dihitung otomatis)</span>
                </div>
                <p class="mt-1 text-sm text-gray-500">Total akan dihitung otomatis dari jumlah laki-laki + perempuan</p>
            </div>

            <!-- Keterangan (Optional) -->
            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-info-circle text-gray-600 mr-1"></i>
                    Keterangan <span class="text-gray-400">(Opsional)</span>
                </label>
                <textarea name="keterangan" 
                          id="keterangan"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('keterangan') border-red-500 @enderror"
                          placeholder="Tambahkan keterangan atau catatan khusus tentang wilayah ini...">{{ old('keterangan', $wilayah->keterangan) }}</textarea>
                @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Maksimal 500 karakter</p>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on text-green-600 mr-1"></i>
                    Status Data
                </label>
                <select name="status" 
                        id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('status') border-red-500 @enderror">
                    <option value="aktif" {{ old('status', $wilayah->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $wilayah->status) == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Data dengan status aktif akan ditampilkan di halaman statistik</p>
            </div>

            <!-- Change History Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-800 mb-2">
                    <i class="fas fa-history mr-1"></i>
                    Informasi Data
                </h4>
                <div class="text-sm text-blue-700 space-y-1">
                    <p><strong>Dibuat:</strong> {{ $wilayah->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Terakhir diperbarui:</strong> {{ $wilayah->updated_at->format('d/m/Y H:i') }}</p>
                    @if($wilayah->created_at != $wilayah->updated_at)
                        <p class="text-orange-600"><i class="fas fa-info-circle mr-1"></i>Data ini telah diubah sebelumnya</p>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t">
                <button type="submit" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Update Data Wilayah
                </button>
                <a href="{{ route('admin.statistik.wilayah.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-yellow-900 mb-2">Perhatian</h3>
                <div class="text-yellow-800 space-y-2">
                    <p>• Pastikan data yang dimasukkan akurat dan terkini</p>
                    <p>• Perubahan data akan langsung terlihat di halaman statistik publik</p>
                    <p>• Total penduduk akan dihitung otomatis dari jumlah laki-laki + perempuan</p>
                    <p>• Jika status diubah menjadi "Non-aktif", data tidak akan ditampilkan di halaman publik</p>
                </div>
            </div>
        </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript for Auto Calculation -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const lakiInput = document.getElementById('laki_laki');
        const perempuanInput = document.getElementById('perempuan');
        const totalDisplay = document.getElementById('total_display');

        function calculateTotal() {
            const laki = parseInt(lakiInput.value) || 0;
            const perempuan = parseInt(perempuanInput.value) || 0;
            const total = laki + perempuan;
            totalDisplay.textContent = total.toLocaleString('id-ID');
        }

        lakiInput.addEventListener('input', calculateTotal);
        perempuanInput.addEventListener('input', calculateTotal);
        
        // Calculate on page load
        calculateTotal();
    });
    </script>
@endsection
