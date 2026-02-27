@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Pengelolaan Surat', 'url' => '#'],
];
$jenisSurat = [
    'domisili' => 'Surat Keterangan Domisili',
    'ktp' => 'Surat Keterangan KTP',
    'kk' => 'Surat Keterangan KK',
    'skck' => 'Surat Pengantar SKCK',
    'kematian' => 'Surat Keterangan Kematian',
    'kelahiran' => 'Surat Keterangan Kelahiran',
    'belum_menikah' => 'Surat Keterangan Belum Menikah',
    'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
    'usaha' => 'Surat Keterangan Usaha',
    'kehilangan' => 'Surat Keterangan Kehilangan',
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
        <div class="max-w-7xl mx-auto w-full">
            <h1 class="text-2xl md:text-3xl font-bold text-[#0088cc] mb-8 text-center md:text-left">Pengelolaan Surat Keterangan</h1>
            <!-- Filter and Search Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                    <!-- Filter Controls -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Jenis Surat Filter -->
                        <div class="flex-1 min-w-64">
                            <label for="jenisSuratSelect" class="block text-sm font-semibold text-gray-700 mb-2">
                                Pilih Jenis Surat Keterangan:
                            </label>
                            <select id="jenisSuratSelect" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                                <option value="">-- Semua Jenis Surat --</option>
                                @foreach($jenisSurat as $key => $label)
                                    <option value="{{ $key }}" {{ ($jenisFilter ?? '') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @if(!empty($jenisFilter ?? ''))
                                <div class="mt-1 text-xs text-blue-600">
                                    <i class="fas fa-filter mr-1"></i>
                                    Filter aktif: {{ $jenisSurat[$jenisFilter] ?? ucfirst($jenisFilter) }}
                                </div>
                            @endif
                        </div>

                        <!-- Search Input -->
                        <div class="flex-1 min-w-64">
                            <label for="searchInput" class="block text-sm font-semibold text-gray-700 mb-2">
                                Cari Surat:
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="searchInput"
                                       placeholder="Cari nama pemohon, NIK, atau status..."
                                       value="{{ $search ?? '' }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                @if(!empty($search ?? ''))
                                    <button type="button" 
                                            id="clearSearch"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                            @if(!empty($search ?? ''))
                                <div class="mt-1 text-xs text-green-600">
                                    <i class="fas fa-search mr-1"></i>
                                    Mencari: "{{ $search }}"
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Results Info and Per Page -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <!-- Items per page -->
                        <div class="flex items-center gap-2">
                            <label for="perPageSelect" class="text-sm text-gray-600 font-medium whitespace-nowrap">
                                Tampilkan:
                            </label>
                            <select id="perPageSelect" 
                                    class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="10" {{ ($perPage ?? 20) == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ ($perPage ?? 20) == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ ($perPage ?? 20) == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span class="text-sm text-gray-600 font-medium whitespace-nowrap">
                                per halaman
                            </span>
                        </div>

                        <!-- Total results -->
                        <div class="text-sm text-gray-600 font-medium">
                            @if(isset($pagination) && isset($items))
                                @if(!empty($jenisFilter ?? '') || !empty($search ?? ''))
                                    <div class="text-blue-600">
                                        <i class="fas fa-filter mr-1"></i>
                                        Ditemukan: {{ $pagination->total }} dari total surat
                                    </div>
                                @else
                                    <div>
                                        <i class="fas fa-files mr-1"></i>
                                        Total: {{ $pagination->total }} surat
                                    </div>
                                @endif
                            @else
                                Total: {{ count($suratList ?? []) }} surat
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(!empty($jenisFilter ?? '') || !empty($search ?? ''))
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm text-gray-600 font-medium">Filter aktif:</span>
                            
                            @if(!empty($jenisFilter ?? ''))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $jenisSurat[$jenisFilter] ?? ucfirst($jenisFilter) }}
                                    <button type="button" 
                                            onclick="clearJenisFilter()"
                                            class="ml-2 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif

                            @if(!empty($search ?? ''))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    "{{ $search }}"
                                    <button type="button" 
                                            onclick="clearSearchFilter()"
                                            class="ml-2 text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif

                            <button type="button" 
                                    onclick="clearAllFilters()"
                                    class="text-sm text-gray-500 hover:text-gray-700 underline">
                                Hapus semua filter
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Loading State -->
            <div id="loadingState" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="text-gray-700 font-medium">Memuat data...</span>
                </div>
            </div>
            
            <div id="dataPreviewContainer" class="overflow-x-auto">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <table class="min-w-full w-full bg-white">
                        <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                            <tr>
                                <th class="px-4 py-4 text-center font-semibold">No</th>
                                <th class="px-4 py-4 text-left font-semibold">Nama Pemohon</th>
                                <th class="px-4 py-4 text-left font-semibold">Jenis Surat</th>
                                <th class="px-4 py-4 text-left font-semibold">Tanggal Pengajuan</th>
                                <th class="px-4 py-4 text-left font-semibold">Status</th>
                                <th class="px-4 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="suratTableBody" class="divide-y divide-gray-200">
                            @php
                                // Support both new ($items) and old ($suratList) data structures
                                $displayItems = isset($items) ? $items : (isset($suratList) ? $suratList : []);
                                $itemsOffset = isset($pagination) ? $pagination->from - 1 : 0;
                            @endphp
                            
                            @if(count($displayItems) > 0)
                                @foreach($displayItems as $index => $surat)
                                    <tr class="border-b hover:bg-gray-50 transition-colors" data-jenis="{{ $surat->jenis_surat }}">
                                        <td class="py-3 px-4 text-center font-medium">{{ $itemsOffset + $index + 1 }}</td>
                                        <td class="py-3 px-4">
                                            {{ $surat->nama_pemohon ?? $surat->nama_lengkap ?? $surat->nama ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $jenisSurat[$surat->jenis_surat] ?? ucfirst($surat->jenis_surat) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">{{ $surat->created_at ? $surat->created_at->format('d/m/Y') : '-' }}</td>
                                        <td class="py-3 px-4">
                                            @php
                                                $status = $surat->status ?? 'menunggu';

                                                $verificationProgress = 0;
                                                if (in_array($surat->jenis_surat, ['domisili', 'tidak_mampu', 'belum_menikah', 'skck', 'ktp', 'kk', 'usaha', 'kematian', 'kelahiran', 'kehilangan']) && ($surat->tahapan_verifikasi ?? false)) {
                                                    $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
                                                    if (is_array($stages) && count($stages) > 0) {
                                                        $totalStages = count($stages);
                                                        $completedStages = collect($stages)->where('status', 'completed')->count();
                                                        $verificationProgress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                                                    }
                                                }

                                                $standardStatus = 'menunggu verifikasi';
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                
                                                if ($verificationProgress >= 100) {
                                                    $standardStatus = 'sudah diverifikasi';
                                                    $statusClass = 'bg-green-100 text-green-800';
                                                } elseif ($verificationProgress > 0 && $verificationProgress < 100) {
                                                    $standardStatus = 'diproses';
                                                    $statusClass = 'bg-blue-100 text-blue-800';
                                                } elseif (str_contains(strtolower($status), 'menunggu') || str_contains(strtolower($status), 'pending')) {
                                                    $standardStatus = 'menunggu verifikasi';
                                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                                } elseif (str_contains(strtolower($status), 'sudah diverifikasi') || str_contains(strtolower($status), 'selesai')) {
                                                    $standardStatus = 'sudah diverifikasi';
                                                    $statusClass = 'bg-green-100 text-green-800';
                                                } elseif (str_contains(strtolower($status), 'diproses')) {
                                                    $standardStatus = 'diproses';
                                                    $statusClass = 'bg-blue-100 text-blue-800';
                                                } elseif (str_contains(strtolower($status), 'ditolak')) {
                                                    $standardStatus = 'ditolak';
                                                    $statusClass = 'bg-red-100 text-red-800';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                {{ $standardStatus }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex gap-1 justify-center flex-wrap">
                                                <a href="{{ url('/admin/verification/' . $surat->jenis_surat . '/' . $surat->id) }}" 
                                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded transition" 
                                                   title="Lihat Tahapan Verifikasi">
                                                    <i class="fas fa-tasks mr-1"></i>Verifikasi
                                                </a>
                                                
                                                @php
                                                    \Log::info('Admin Complete button check', [
                                                        'surat_id' => $surat->id,
                                                        'jenis_surat' => $surat->jenis_surat,
                                                        'raw_status' => $status,
                                                        'standard_status' => $standardStatus,
                                                        'verification_progress' => $verificationProgress,
                                                        'button_will_show' => ($standardStatus !== 'sudah diverifikasi' && $standardStatus !== 'ditolak')
                                                    ]);
                                                @endphp

                                                @if($standardStatus !== 'sudah diverifikasi' && $standardStatus !== 'ditolak')

                                                    <form action="{{ route('admin.surat.complete', $surat->id) }}" method="POST" 
                                                          class="inline" 
                                                          onsubmit="return confirm('Yakin ingin menandai surat ini sebagai sudah diverifikasi?')">
                                                        @csrf
                                                        <input type="hidden" name="jenis_surat" value="{{ $surat->jenis_surat }}">
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition"
                                                                title="Tandai Sudah Diverifikasi (Alt Complete) - Current Status: {{ $standardStatus }}">
                                                            <i class="fas fa-check-double mr-1"></i>Alt-Complete
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-gray-400" title="Alt-Complete tidak tersedia untuk status: {{ $standardStatus }}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </span>
                                                @endif

                                                <a href="{{ route('surat.edit', $surat->id) }}" 
                                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition"
                                                   title="Edit Surat">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>

                                                <a href="{{ route('surat.show', $surat->id) }}?jenis={{ $surat->jenis_surat }}" 
                                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded transition"
                                                   title="Lihat Detail Surat">
                                                    <i class="fas fa-eye mr-1"></i>Detail
                                                </a>
                                                
                                                <!-- Tombol Print PDF -->
                                                <a href="/admin/surat/print-pdf/{{ strtolower($surat->jenis_surat) }}/{{ $surat->id }}" target="_blank" 
                                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition" 
                                                   title="Print PDF">
                                                    <i class="fas fa-print mr-1"></i>Print
                                                </a>
                                                
                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('surat.destroy', $surat->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition" 
                                                            onclick="return confirm('Yakin ingin menghapus surat ini?')"
                                                            title="Hapus Surat">
                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                                            <p class="text-lg">
                                                @if(!empty($jenisFilter ?? '') || !empty($search ?? ''))
                                                    Tidak ada data surat yang sesuai dengan filter
                                                @else
                                                    Tidak ada data surat
                                                @endif
                                            </p>
                                            @if(!empty($jenisFilter ?? '') || !empty($search ?? ''))
                                                <button type="button" 
                                                        onclick="clearAllFilters()"
                                                        class="mt-2 text-sm text-blue-600 hover:text-blue-800 underline">
                                                    Hapus semua filter
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls -->
                @if(isset($pagination) && $pagination->has_pages)
                    <div class="bg-white border-t px-4 py-3 flex flex-col sm:flex-row items-center justify-between">
                        <div class="flex-1 flex flex-col sm:flex-row items-center justify-between">
                            <div class="mb-2 sm:mb-0">
                                <p class="text-sm text-gray-700">
                                    Menampilkan 
                                    <span class="font-medium">{{ $pagination->from }}</span>
                                    sampai 
                                    <span class="font-medium">{{ $pagination->to }}</span>
                                    dari 
                                    <span class="font-medium">{{ $pagination->total }}</span>
                                    hasil
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <!-- Previous Button -->
                                    @if($pagination->prev_page_url)
                                        <a href="{{ $pagination->prev_page_url }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    @endif
                                    
                                    <!-- Page Numbers -->
                                    @php
                                        $start = max(1, $pagination->current_page - 2);
                                        $end = min($pagination->last_page, $pagination->current_page + 2);
                                    @endphp
                                    
                                    @if($start > 1)
                                        <a href="{{ $pagination->first_page_url }}" class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>
                                        @if($start > 2)
                                            <span class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                                        @endif
                                    @endif
                                    
                                    @for($i = $start; $i <= $end; $i++)
                                        @if($i == $pagination->current_page)
                                            <span aria-current="page" class="z-10 relative inline-flex items-center px-3 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">{{ $i }}</span>
                                        @else
                                            <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->all(), ['page' => $i])) }}" class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">{{ $i }}</a>
                                        @endif
                                    @endfor
                                    
                                    @if($end < $pagination->last_page)
                                        @if($end < $pagination->last_page - 1)
                                            <span class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                                        @endif
                                        <a href="{{ $pagination->last_page_url }}" class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">{{ $pagination->last_page }}</a>
                                    @endif
                                    
                                    <!-- Next Button -->
                                    @if($pagination->next_page_url)
                                        <a href="{{ $pagination->next_page_url }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
@include('admin.partials.footer')
@endsection
@push('styles')
<style>
    /* Pagination custom styling */
    #paginationContainer .relative.inline-flex.items-center {
        transition: all 0.2s ease-in-out;
    }
    
    #paginationContainer .relative.inline-flex.items-center:hover:not(:disabled) {
        background-color: #f3f4f6;
        transform: translateY(-1px);
    }
    
    #paginationContainer .z-10 {
        background-color: #3b82f6 !important;
        color: white !important;
        border-color: #3b82f6 !important;
    }
    
    /* Loading animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .loading-row {
        animation: pulse 1.5s ease-in-out infinite;
    }

    @media (max-width: 640px) {
        #paginationContainer {
            padding: 1rem;
        }
        
        #paginationContainer .flex-col {
            gap: 1rem;
        }
        
        #pageNumbers button {
            padding: 0.5rem 0.75rem;
        }
    }
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Admin Surat Management System Initialized');
    
    const jenisSelect = document.getElementById('jenisSuratSelect');
    const perPageSelect = document.getElementById('perPageSelect');
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    
    // Current filter values from server
    const currentFilters = {
        jenis: '{{ $jenisFilter ?? '' }}',
        search: '{{ $search ?? '' }}',
        perPage: {{ $perPage ?? 20 }}
    };
    
    console.log('📊 Current filters:', currentFilters);
    
    // Helper function to build URL with filters
    function buildFilterUrl(newFilters = {}) {
        const params = new URLSearchParams();
        
        const filters = {
            jenis_surat: newFilters.jenis !== undefined ? newFilters.jenis : currentFilters.jenis,
            search: newFilters.search !== undefined ? newFilters.search : currentFilters.search,
            per_page: newFilters.perPage !== undefined ? newFilters.perPage : currentFilters.perPage,
            page: newFilters.page || 1 // Always reset to page 1 when filters change
        };
        
        // Only add non-empty filters
        Object.entries(filters).forEach(([key, value]) => {
            if (value) {
                params.set(key, value);
            }
        });
        
        return window.location.pathname + '?' + params.toString();
    }
    
    function applyFilters(newFilters = {}) {
        const url = buildFilterUrl(newFilters);
        console.log('🔄 Applying filters, redirecting to:', url);
        
        // Show loading state
        const loadingElement = document.getElementById('loadingState');
        if (loadingElement) {
            loadingElement.style.display = 'block';
        }
        
        // Add loading class to body to show loading cursor
        document.body.style.cursor = 'wait';
        
        window.location.href = url;
    }

    // Event listeners for filter controls
    if (jenisSelect) {
        jenisSelect.addEventListener('change', function() {
            console.log('📝 Jenis filter changed:', this.value);
            this.disabled = true; // Prevent multiple clicks
            applyFilters({ jenis: this.value });
        });
    }
    
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            console.log('📄 Per page changed:', this.value);
            this.disabled = true; // Prevent multiple clicks
            applyFilters({ perPage: parseInt(this.value) });
        });
    }

    // Search input with debounce
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchValue = this.value.trim();
            
            console.log('🔍 Search input changed:', searchValue);
            
            searchTimeout = setTimeout(() => {
                applyFilters({ search: searchValue });
            }, 800); 
        });
    }
    
    // Clear search button
    if (clearSearch) {
        clearSearch.addEventListener('click', function() {
            console.log('❌ Clearing search');
            applyFilters({ search: '' });
        });
    }
    
    // Global functions for filter badges and buttons
    window.clearJenisFilter = function() {
        console.log('❌ Clearing jenis filter');
        applyFilters({ jenis: '' });
    };
    
    window.clearSearchFilter = function() {
        console.log('❌ Clearing search filter');
        applyFilters({ search: '' });
    };
    
    window.clearAllFilters = function() {
        console.log('🗑️ Clearing all filters');
        applyFilters({ jenis: '', search: '', page: 1 });
    };

    // Function for deleting surat
    window.deleteSurat = function(id) {
        if (confirm('Yakin ingin menghapus surat ini?')) {
            const deleteBtn = event.target.closest('button');
            const originalContent = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...';
            deleteBtn.disabled = true;
            
            fetch(`/admin/surat/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus surat');
                    deleteBtn.innerHTML = originalContent;
                    deleteBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menghapus surat');
                deleteBtn.innerHTML = originalContent;
                deleteBtn.disabled = false;
            });
        }
    };

    // Function for completing surat verification
    window.completeSurat = function(id, jenis) {
        console.log('🔄 completeSurat function called');
        console.log('Parameters:', { id, jenis });
        
        if (confirm('Yakin ingin menandai surat ini sebagai "Sudah Diverifikasi"?')) {
            console.log('✅ User confirmed the action');
            
            const completeBtn = event.target.closest('button');
            const originalContent = completeBtn.innerHTML;
            
            completeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Processing...';
            completeBtn.disabled = true;
            
            const url = `/admin/surat/${id}/complete`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            
            const requestData = {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    jenis_surat: jenis
                })
            };
            
            if (csrfToken) {
                requestData.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
            }
            
            fetch(url, requestData)
            .then(response => {
                console.log('📡 Response received:', response.status);
                
                if (response.status === 419) {
                    throw new Error('CSRF_TOKEN_MISMATCH');
                }
                
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('INVALID_RESPONSE_FORMAT');
                    }
                });
            })
            .then(data => {
                if (data && data.success) {
                    console.log('✅ Success! Reloading...');
                    alert('✅ Verifikasi surat berhasil diselesaikan!');
                    location.reload();
                } else {
                    const errorMessage = data && data.message ? data.message : 'Unknown error occurred';
                    alert('Gagal mengubah status: ' + errorMessage);
                    
                    completeBtn.innerHTML = originalContent;
                    completeBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('💥 Error occurred:', error);
                
                let errorMessage = 'Terjadi kesalahan tidak dikenal';
                
                if (error.message === 'CSRF_TOKEN_MISMATCH') {
                    errorMessage = 'Session expired. Halaman akan di-refresh.';
                    alert('🔒 ' + errorMessage);
                    location.reload();
                    return;
                } else {
                    errorMessage = error.message || 'Network error occurred';
                }
                
                alert('❌ ' + errorMessage);
                
                completeBtn.innerHTML = originalContent;
                completeBtn.disabled = false;
            });
        }
    };
    
    // Handle success/error messages from Laravel session
    @if(session('success'))
        alert('✅ {{ session('success') }}');
    @endif
    
    @if(session('error'))
        alert('❌ {{ session('error') }}');
    @endif
    
    console.log('✅ Admin surat management initialized successfully');
});
</script>
@endpush
</script>