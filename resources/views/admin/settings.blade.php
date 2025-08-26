@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Pengaturan Surat', 'url' => '#'],
];
@endphp

<!-- Admin Layout -->
<div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-50">
    @include('admin.partials.navbar', ['breadcrumbs' => $breadcrumbs])
    @include('admin.partials.sidebar')
    
    <!-- Main Content -->
    <main class="lg:ml-64 pt-14 sm:pt-16 transition-all duration-300" :class="{'lg:ml-16': $store.sidebar?.isMinimized}">
        <div class="p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Pengaturan Surat Keterangan</h1>
                <p class="text-gray-600">Ubah informasi kepala desa yang akan ditampilkan pada surat keterangan</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="kepala_desa_nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kepala Desa
                        </label>
                        <input 
                            type="text" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('kepala_desa_nama') border-red-500 @enderror" 
                            id="kepala_desa_nama" 
                            name="kepala_desa_nama" 
                            value="{{ old('kepala_desa_nama', $settings['kepala_desa_nama']) }}" 
                            placeholder="Masukkan nama kepala desa"
                            required
                        >
                        @error('kepala_desa_nama')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Nama ini akan ditampilkan pada tanda tangan surat keterangan</p>
                    </div>
                    
                    <div>
                        <label for="kepala_desa_jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jabatan Kepala Desa
                        </label>
                        <input 
                            type="text" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent @error('kepala_desa_jabatan') border-red-500 @enderror" 
                            id="kepala_desa_jabatan" 
                            name="kepala_desa_jabatan" 
                            value="{{ old('kepala_desa_jabatan', $settings['kepala_desa_jabatan']) }}" 
                            placeholder="Masukkan jabatan kepala desa"
                            required
                        >
                        @error('kepala_desa_jabatan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Jabatan ini akan ditampilkan di bawah nama pada surat keterangan</p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h6 class="font-semibold text-blue-800 mb-2">Informasi Penting</h6>
                            <ul class="text-blue-700 text-sm space-y-1">
                                <li>• Perubahan akan diterapkan pada semua surat keterangan yang dicetak setelah disimpan</li>
                                <li>• Pastikan nama dan jabatan sesuai dengan data resmi desa</li>
                                <li>• Gunakan format nama lengkap tanpa gelar</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="bg-[#0088cc] text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <button type="button" onclick="window.location.href='{{ route('admin.dashboard') }}'" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition font-semibold text-center flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 lg:p-8 mt-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-eye text-[#0088cc] mr-3"></i>
                Preview Tanda Tangan
            </h3>
            <p class="text-gray-600 mb-6">Contoh tampilan tanda tangan pada surat keterangan</p>
            
            <div class="bg-gray-50 rounded-lg p-6 sm:p-8">
                <div class="text-center">
                    <div class="inline-block">
                        <p class="text-lg font-bold text-gray-900 mb-1" id="preview-nama">{{ $settings['kepala_desa_nama'] ?: 'Nama Kepala Desa' }}</p>
                        <p class="text-gray-600" id="preview-jabatan">{{ $settings['kepala_desa_jabatan'] ?: 'Jabatan Kepala Desa' }}</p>
                        <div class="border-t-2 border-gray-400 w-48 mx-auto mt-4"></div>
                        <p class="text-xs text-gray-500 mt-2">Contoh tampilan pada PDF surat keterangan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
</div>

<script>
// Simple live preview update
document.addEventListener('DOMContentLoaded', function() {
    const namaInput = document.getElementById('kepala_desa_nama');
    const jabatanInput = document.getElementById('kepala_desa_jabatan');
    
    function updatePreview() {
        const previewNama = document.getElementById('preview-nama');
        const previewJabatan = document.getElementById('preview-jabatan');
        
        if (previewNama && previewJabatan) {
            previewNama.textContent = namaInput.value || 'Nama Kepala Desa';
            previewJabatan.textContent = jabatanInput.value || 'Jabatan Kepala Desa';
        }
    }
    
    if (namaInput && jabatanInput) {
        namaInput.addEventListener('input', updatePreview);
        jabatanInput.addEventListener('input', updatePreview);
    }
});
</script>
@endsection
