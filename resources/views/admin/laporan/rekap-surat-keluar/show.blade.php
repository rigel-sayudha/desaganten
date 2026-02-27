@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Laporan', 'url' => '#'],
    ['label' => 'Data Rekap Surat Keluar', 'url' => route('admin.laporan.rekap-surat-keluar.index')],
    ['label' => 'Detail Data', 'url' => '#'],
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
        <h1 class="text-2xl font-bold text-gray-900">Detail Data Rekap Surat Keluar</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.laporan.rekap-surat-keluar.edit', $rekapSurat->id) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.laporan.rekap-surat-keluar.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detail Informasi -->
        <div class="lg:col-span-2">
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Surat</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Surat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $rekapSurat->tanggal_surat_formatted }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nomor Surat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $rekapSurat->nomor_surat ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nama Pemohon</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $rekapSurat->nama_pemohon }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Jenis Surat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $rekapSurat->jenis_surat }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600">Untuk Keperluan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $rekapSurat->untuk_keperluan }}</p>
                    </div>

                    @if($rekapSurat->keterangan)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600">Keterangan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $rekapSurat->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status dan Metadata -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Surat</h3>
                <div class="text-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $rekapSurat->status_badge_class }}">
                        {{ ucfirst($rekapSurat->status) }}
                    </span>
                </div>
            </div>

            <!-- Metadata Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600">ID Rekap</label>
                        <p class="text-sm text-gray-900">#{{ $rekapSurat->id }}</p>
                    </div>

                    @if($rekapSurat->surat_type && $rekapSurat->surat_id)
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Tipe Data Terkait</label>
                        <p class="text-sm text-gray-900">{{ $rekapSurat->surat_type }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600">ID Data Terkait</label>
                        <p class="text-sm text-gray-900">#{{ $rekapSurat->surat_id }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-xs font-medium text-gray-600">Dibuat Pada</label>
                        <p class="text-sm text-gray-900">{{ $rekapSurat->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($rekapSurat->updated_at != $rekapSurat->created_at)
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Terakhir Diupdate</label>
                        <p class="text-sm text-gray-900">{{ $rekapSurat->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    @if($rekapSurat->status !== 'selesai')
                    <form action="{{ route('admin.laporan.rekap-surat-keluar.update', $rekapSurat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tanggal_surat" value="{{ $rekapSurat->tanggal_surat->format('Y-m-d') }}">
                        <input type="hidden" name="nomor_surat" value="{{ $rekapSurat->nomor_surat }}">
                        <input type="hidden" name="nama_pemohon" value="{{ $rekapSurat->nama_pemohon }}">
                        <input type="hidden" name="jenis_surat" value="{{ $rekapSurat->jenis_surat }}">
                        <input type="hidden" name="untuk_keperluan" value="{{ $rekapSurat->untuk_keperluan }}">
                        <input type="hidden" name="keterangan" value="{{ $rekapSurat->keterangan }}">
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                            Tandai Selesai
                        </button>
                    </form>
                    @endif

                    @if($rekapSurat->status !== 'diproses' && $rekapSurat->status !== 'selesai')
                    <form action="{{ route('admin.laporan.rekap-surat-keluar.update', $rekapSurat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tanggal_surat" value="{{ $rekapSurat->tanggal_surat->format('Y-m-d') }}">
                        <input type="hidden" name="nomor_surat" value="{{ $rekapSurat->nomor_surat }}">
                        <input type="hidden" name="nama_pemohon" value="{{ $rekapSurat->nama_pemohon }}">
                        <input type="hidden" name="jenis_surat" value="{{ $rekapSurat->jenis_surat }}">
                        <input type="hidden" name="untuk_keperluan" value="{{ $rekapSurat->untuk_keperluan }}">
                        <input type="hidden" name="keterangan" value="{{ $rekapSurat->keterangan }}">
                        <input type="hidden" name="status" value="diproses">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                            Mulai Proses
                        </button>
                    </form>
                    @endif

                    <button onclick="deleteRekap({{ $rekapSurat->id }})" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 text-sm">
                        Hapus Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus data rekap surat ini?</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

        </main>
    </div>

<script>
function deleteRekap(id) {
    document.getElementById('deleteForm').action = `{{ route('admin.laporan.rekap-surat-keluar.index') }}/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
