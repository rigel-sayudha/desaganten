@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Laporan', 'url' => '#'],
    ['label' => 'Data Rekap Surat Keluar', 'url' => '#'],
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
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 truncate">Total Surat</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 truncate">Selesai</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['selesai'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 truncate">Diproses</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['diproses'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 truncate">Bulan Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['bulan_ini'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Rekap Surat Keluar</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola dan pantau rekap surat keluar dari berbagai jenis surat</p>
        </div>
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <button onclick="syncData()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Sync Data
            </button>
            <a href="{{ route('admin.laporan.rekap-surat-keluar.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Data
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Filter Data</h3>
            <button type="button" onclick="toggleFilter()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-chevron-down transition-transform duration-200" id="filterIcon"></i>
            </button>
        </div>
        <div id="filterContent" class="space-y-4">
            <form method="GET" action="{{ route('admin.laporan.rekap-surat-keluar.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                    <select name="jenis_surat" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Jenis</option>
                        <option value="Surat Keterangan Domisili" {{ request('jenis_surat') == 'Surat Keterangan Domisili' ? 'selected' : '' }}>Surat Keterangan Domisili</option>
                        <option value="Surat Keterangan Tidak Mampu" {{ request('jenis_surat') == 'Surat Keterangan Tidak Mampu' ? 'selected' : '' }}>Surat Keterangan Tidak Mampu</option>
                        <option value="Surat Keterangan Usaha" {{ request('jenis_surat') == 'Surat Keterangan Usaha' ? 'selected' : '' }}>Surat Keterangan Usaha</option>
                        <option value="Surat Pengantar" {{ request('jenis_surat') == 'Surat Pengantar' ? 'selected' : '' }}>Surat Pengantar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="lg:col-span-4 flex justify-start space-x-3 pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        Filter Data
                    </button>
                    <a href="{{ route('admin.laporan.rekap-surat-keluar.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-undo mr-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Daftar Rekap Surat</h3>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $rekapSurat->firstItem() ?? 0 }} - {{ $rekapSurat->lastItem() ?? 0 }} dari {{ $rekapSurat->total() }} data
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemohon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Untuk Keperluan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rekapSurat as $index => $rekap)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rekapSurat->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rekap->tanggal_surat_formatted }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                {{ $rekap->nomor_surat ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-medium">{{ $rekap->nama_pemohon }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $rekap->jenis_surat }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate" title="{{ $rekap->untuk_keperluan }}">
                                {{ Str::limit($rekap->untuk_keperluan, 30) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rekap->status_badge_class }}">
                                {{ ucfirst($rekap->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-1">
                                <a href="{{ route('admin.laporan.rekap-surat-keluar.show', $rekap->id) }}" 
                                   class="inline-flex items-center p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-100 rounded-lg transition-colors duration-150" 
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.laporan.rekap-surat-keluar.edit', $rekap->id) }}" 
                                   class="inline-flex items-center p-2 text-yellow-600 hover:text-yellow-900 hover:bg-yellow-100 rounded-lg transition-colors duration-150" 
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button onclick="deleteRekap({{ $rekap->id }})" 
                                        class="inline-flex items-center p-2 text-red-600 hover:text-red-900 hover:bg-red-100 rounded-lg transition-colors duration-150" 
                                        title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">Tidak ada data rekap surat keluar</p>
                                <p class="text-gray-400 text-sm mt-1">Gunakan tombol "Sync Data" untuk mengambil data dari sistem</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($rekapSurat->hasPages())
    <div class="bg-white px-6 py-4 flex items-center justify-between border border-gray-200 rounded-lg mt-6">
        <div class="flex-1 flex justify-between sm:hidden">
            <!-- Mobile pagination -->
            @if ($rekapSurat->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $rekapSurat->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    Sebelumnya
                </a>
            @endif

            @if ($rekapSurat->hasMorePages())
                <a href="{{ $rekapSurat->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    Berikutnya
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                    Berikutnya
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Menampilkan
                    <span class="font-medium">{{ $rekapSurat->firstItem() }}</span>
                    sampai
                    <span class="font-medium">{{ $rekapSurat->lastItem() }}</span>
                    dari
                    <span class="font-medium">{{ $rekapSurat->total() }}</span>
                    hasil
                </p>
            </div>
            <div>
                {{ $rekapSurat->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
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
// Filter toggle functionality
function toggleFilter() {
    const content = document.getElementById('filterContent');
    const icon = document.getElementById('filterIcon');
    
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// Initialize filter state
document.addEventListener('DOMContentLoaded', function() {
    const hasActiveFilters = {{ request()->hasAny(['jenis_surat', 'status', 'dari_tanggal', 'sampai_tanggal']) ? 'true' : 'false' }};
    if (!hasActiveFilters) {
        document.getElementById('filterContent').classList.add('hidden');
        document.getElementById('filterIcon').classList.add('rotate-180');
    }
});

function deleteRekap(id) {
    document.getElementById('deleteForm').action = `{{ route('admin.laporan.rekap-surat-keluar.index') }}/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

async function syncData() {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Syncing...';
    
    try {
        const response = await fetch('{{ route("admin.laporan.rekap-surat-keluar.sync") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Use SweetAlert2 for better UX
            Swal.fire({
                title: 'Sync Berhasil!',
                text: `${data.synced} data berhasil disinkronkan.`,
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat sync data',
                icon: 'error'
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat sync data',
            icon: 'error'
        });
    } finally {
        button.disabled = false;
        button.innerHTML = originalText;
    }
}
</script>
@endsection
