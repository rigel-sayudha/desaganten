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
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <div class="md:w-1/2">
                    <label for="jenisSuratSelect" class="block font-semibold mb-2">Pilih Jenis Surat Keterangan:</label>
                    <select id="jenisSuratSelect" class="border rounded px-3 py-2 w-full max-w-xs focus:outline-none focus:border-blue-500">
                        <option value="">-- Semua Jenis Surat --</option>
                        @foreach($jenisSurat as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:w-1/2 flex flex-col md:flex-row items-start md:items-center justify-end gap-4">
                    <div class="flex items-center gap-2">
                        <label for="perPageSelect" class="text-sm text-gray-600 font-medium whitespace-nowrap">
                            Tampilkan:
                        </label>
                        <select id="perPageSelect" class="border rounded px-2 py-1 text-sm focus:outline-none focus:border-blue-500">
                            <option value="10">10</option>
                            <option value="20" selected>20</option>
                            <option value="50">50</option>
                        </select>
                        <span class="text-sm text-gray-600 font-medium whitespace-nowrap">
                            per halaman
                        </span>
                    </div>
                    <span id="totalSurat" class="text-sm text-gray-600 font-medium">
                        Total: {{ count($suratList) }} surat
                    </span>
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
                            @foreach($suratList as $index => $surat)
                                <tr class="border-b hover:bg-gray-50 transition-colors" data-jenis="{{ $surat->jenis_surat }}">
                                    <td class="py-3 px-4 text-center font-medium">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4">{{ $surat->nama_pemohon ?? $surat->nama_lengkap ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $jenisSurat[$surat->jenis_surat] ?? ucfirst($surat->jenis_surat) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">{{ $surat->created_at ? $surat->created_at->format('d/m/Y') : '-' }}</td>
                                    <td class="py-3 px-4">
                                        @php
                                            $status = $surat->status ?? 'menunggu';
                                            
                                            // Check if verification is 100% complete based on stages
                                            $verificationProgress = 0;
                                            if (in_array($surat->jenis_surat, ['domisili', 'tidak_mampu', 'belum_menikah', 'skck', 'ktp', 'kk', 'usaha', 'kematian', 'kelahiran', 'kehilangan']) && ($surat->tahapan_verifikasi ?? false)) {
                                                $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
                                                if (is_array($stages) && count($stages) > 0) {
                                                    $totalStages = count($stages);
                                                    $completedStages = collect($stages)->where('status', 'completed')->count();
                                                    $verificationProgress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                                                }
                                            }
                                            
                                            // Standardize status names - if verification is 100%, override status
                                            $standardStatus = 'menunggu verifikasi';
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            
                                            if ($verificationProgress >= 100) {
                                                // If verification stages are 100% complete, status should be "sudah diverifikasi"
                                                $standardStatus = 'sudah diverifikasi';
                                                $statusClass = 'bg-green-100 text-green-800';
                                            } elseif ($verificationProgress > 0 && $verificationProgress < 100) {
                                                // If verification is in progress
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
                                            <!-- Tombol Verifikasi -->
                                            <a href="{{ url('/admin/verification/' . $surat->jenis_surat . '/' . $surat->id) }}" 
                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded transition" 
                                               title="Lihat Tahapan Verifikasi">
                                                <i class="fas fa-tasks mr-1"></i>Verifikasi
                                            </a>
                                            
                                            @php
                                                // Log debug info for admin Complete button visibility
                                                \Log::info('Admin Complete button check', [
                                                    'surat_id' => $surat->id,
                                                    'jenis_surat' => $surat->jenis_surat,
                                                    'raw_status' => $status,
                                                    'standard_status' => $standardStatus,
                                                    'verification_progress' => $verificationProgress,
                                                    'button_will_show' => ($standardStatus !== 'sudah diverifikasi' && $standardStatus !== 'ditolak')
                                                ]);
                                            @endphp

                                            <!-- Tombol Complete (untuk semua status kecuali sudah diverifikasi dan ditolak) -->
                                            @if($standardStatus !== 'sudah diverifikasi' && $standardStatus !== 'ditolak')
                                            
                                                <!-- Method 2: Form Submission Fallback -->
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
                                                <!-- Debug: Show why button is hidden -->
                                                <span class="text-xs text-gray-400" title="Alt-Complete tidak tersedia untuk status: {{ $standardStatus }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                            @endif
                                            
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('surat.edit', $surat->id) }}" 
                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition"
                                               title="Edit Surat">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            
                                            <!-- Tombol Detail/Lihat -->
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
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls -->
                <div id="paginationContainer" class="bg-white border-t px-4 py-3 flex flex-col sm:flex-row items-center justify-between">
                    <div class="flex-1 flex flex-col sm:flex-row items-center justify-between">
                        <div class="mb-2 sm:mb-0">
                            <p class="text-sm text-gray-700">
                                Menampilkan 
                                <span id="showingFrom" class="font-medium">1</span>
                                sampai 
                                <span id="showingTo" class="font-medium">20</span>
                                dari 
                                <span id="showingTotal" class="font-medium">{{ count($suratList) }}</span>
                                hasil
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <!-- Previous Button -->
                                <button id="prevBtn" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="sr-only">Previous</span>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                
                                <!-- Page Numbers Container -->
                                <div id="pageNumbers" class="flex">
                                    <!-- Page numbers will be inserted here by JavaScript -->
                                </div>
                                
                                <!-- Next Button -->
                                <button id="nextBtn" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="sr-only">Next</span>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
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
    
    /* Responsive pagination */
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
    
    const select = document.getElementById('jenisSuratSelect');
    const perPageSelect = document.getElementById('perPageSelect');
    const tbody = document.getElementById('suratTableBody');
    const totalSpan = document.getElementById('totalSurat');
    const showingFrom = document.getElementById('showingFrom');
    const showingTo = document.getElementById('showingTo');
    const showingTotal = document.getElementById('showingTotal');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const pageNumbers = document.getElementById('pageNumbers');
    
    // Store original data untuk filtering
    const originalRows = Array.from(tbody.querySelectorAll('tr'));
    console.log('📊 Total original rows:', originalRows.length);
    
    // Pagination state
    let currentPage = 1;
    let perPage = 20;
    let totalItems = originalRows.length;
    let filteredData = [...originalRows]; // Data setelah filtering
    
    console.log('⚙️ Initial state:', { currentPage, perPage, totalItems });
    
    // ===============================
    // FILTERING FUNCTIONS
    // ===============================
    
    function filterSuratByJenis(jenis = '') {
        console.log('🔍 Filtering by jenis:', jenis);
        
        // Reset pagination to first page
        currentPage = 1;
        
        // Filter data berdasarkan jenis
        if (jenis === '') {
            filteredData = [...originalRows];
            console.log('📋 Showing all data:', filteredData.length);
        } else {
            filteredData = originalRows.filter(row => {
                const jenisAttribute = row.getAttribute('data-jenis');
                const matches = jenisAttribute === jenis;
                if (matches) {
                    console.log('✅ Row matches filter:', jenisAttribute);
                }
                return matches;
            });
            console.log(`📋 Filtered to ${filteredData.length} rows for jenis: ${jenis}`);
        }
        
        totalItems = filteredData.length;
        updateTotalCount(totalItems);
        displayCurrentPage();
    }
    
    function updateTotalCount(count) {
        console.log('📊 Updating total count to:', count);
        totalSpan.textContent = `Total: ${count} surat`;
        showingTotal.textContent = count;
        totalItems = count;
        updatePaginationInfo();
    }

    
    // ===============================
    // PAGINATION FUNCTIONS  
    // ===============================
    
    function updatePaginationInfo() {
        const startIndex = totalItems > 0 ? (currentPage - 1) * perPage + 1 : 0;
        const endIndex = Math.min(currentPage * perPage, totalItems);
        
        console.log('📄 Pagination info:', { startIndex, endIndex, totalItems, currentPage, perPage });
        
        showingFrom.textContent = startIndex;
        showingTo.textContent = endIndex;
        
        // Update pagination buttons
        updatePaginationButtons();
    }

    function updatePaginationButtons() {
        const totalPages = Math.ceil(totalItems / perPage);
        
        console.log('🔘 Updating pagination buttons - Total pages:', totalPages);
        
        // Update prev/next buttons
        prevBtn.disabled = currentPage <= 1;
        nextBtn.disabled = currentPage >= totalPages;
        
        // Clear and rebuild page numbers
        pageNumbers.innerHTML = '';
        
        if (totalPages <= 1) {
            console.log('📄 Single page, hiding pagination');
            return;
        }
        
        // Calculate page range to show
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);
        
        // Show first page if not in range
        if (startPage > 1) {
            addPageButton(1);
            if (startPage > 2) {
                addEllipsis();
            }
        }
        
        // Show page range
        for (let i = startPage; i <= endPage; i++) {
            addPageButton(i, i === currentPage);
        }
        
        // Show last page if not in range
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                addEllipsis();
            }
            addPageButton(totalPages);
        }
        
        console.log('🔘 Pagination buttons updated for pages', startPage, 'to', endPage);
    }

    function addPageButton(pageNum, isActive = false) {
        const button = document.createElement('button');
        button.className = `relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
            isActive 
                ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' 
                : 'border-gray-300 bg-white text-gray-500 hover:bg-gray-50'
        }`;
        button.textContent = pageNum;
        button.onclick = () => goToPage(pageNum);
        pageNumbers.appendChild(button);
    }

    function addEllipsis() {
        const span = document.createElement('span');
        span.className = 'relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700';
        span.textContent = '...';
        pageNumbers.appendChild(span);
    }

    function goToPage(page) {
        console.log('📄 Going to page:', page);
        currentPage = page;
        displayCurrentPage();
        updatePaginationInfo();
    }

    function displayCurrentPage() {
        console.log('🖥️ Displaying current page:', currentPage);
        
        // Hide all rows first
        originalRows.forEach(row => {
            row.style.display = 'none';
        });
        
        // Calculate pagination bounds
        const startIndex = (currentPage - 1) * perPage;
        const endIndex = startIndex + perPage;
        
        console.log('📊 Page bounds:', { startIndex, endIndex, filteredDataLength: filteredData.length });
        
        // Show rows for current page from filtered data
        const visibleRows = filteredData.slice(startIndex, endIndex);
        console.log('👁️ Visible rows count:', visibleRows.length);
        
        visibleRows.forEach((row, index) => {
            row.style.display = '';
            // Update row number
            const firstCell = row.querySelector('td:first-child');
            if (firstCell && !row.classList.contains('no-data-row')) {
                firstCell.textContent = startIndex + index + 1;
            }
        });
        
        // Handle no data message
        if (visibleRows.length === 0 && totalItems === 0) {
            const currentFilter = select.value;
            showNoDataMessage(currentFilter);
        } else {
            hideNoDataMessage();
        }
        
        console.log('✅ Page display updated');
    }

    
    // ===============================
    // HELPER FUNCTIONS
    // ===============================
    
    function showNoDataMessage(jenis) {
        hideNoDataMessage(); // Remove existing message first
        
        console.log('❌ Showing no data message for jenis:', jenis);
        
        const noDataRow = document.createElement('tr');
        noDataRow.className = 'no-data-row border-b';
        
        const jenisText = jenis ? `jenis ${getJenisSuratDisplay(jenis)}` : 'yang dipilih';
        noDataRow.innerHTML = `
            <td colspan="6" class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                <p class="text-lg">Tidak ada data surat ${jenisText}</p>
            </td>
        `;
        tbody.appendChild(noDataRow);
    }
    
    function hideNoDataMessage() {
        const noDataRows = tbody.querySelectorAll('.no-data-row');
        noDataRows.forEach(row => row.remove());
    }
    
    function getJenisSuratDisplay(jenis) {
        const jenisSuratMap = {
            'domisili': 'Surat Keterangan Domisili',
            'ktp': 'Surat Keterangan KTP',
            'kk': 'Surat Keterangan KK',
            'skck': 'Surat Pengantar SKCK',
            'kematian': 'Surat Keterangan Kematian',
            'kelahiran': 'Surat Keterangan Kelahiran',
            'belum_menikah': 'Surat Keterangan Belum Menikah',
            'tidak_mampu': 'Surat Keterangan Tidak Mampu',
            'usaha': 'Surat Keterangan Usaha',
            'kehilangan': 'Surat Keterangan Kehilangan'
        };
        return jenisSuratMap[jenis] || jenis.charAt(0).toUpperCase() + jenis.slice(1);
    }

    function completeSurat(id, jenis) {
        console.log('🔄 completeSurat function called');
        console.log('Parameters:', { id, jenis });
        console.log('Current URL:', window.location.href);
        console.log('Event target:', event ? event.target : 'No event object');
        
        if (confirm('Yakin ingin menandai surat ini sebagai "Sudah Diverifikasi"?')) {
            console.log('✅ User confirmed the action');
            
            // Find the button that was clicked
            let completeBtn = null;
            if (event && event.target) {
                completeBtn = event.target.closest('button');
            }
            
            // If no button found from event, try to find it by data attributes
            if (!completeBtn) {
                completeBtn = document.querySelector(`button[data-surat-id="${id}"][data-jenis="${jenis}"]`);
            }
            
            // If still not found, try a more general selector
            if (!completeBtn) {
                completeBtn = document.querySelector(`button[onclick*="completeSurat(${id}"]`);
            }
            
            if (!completeBtn) {
                console.error('❌ Could not find Complete button element');
                alert('Error: Tidak dapat menemukan tombol Complete. Halaman akan di-refresh.');
                location.reload();
                return;
            }
            
            console.log('🔘 Button found:', completeBtn);
            
            // Store original button content
            const originalContent = completeBtn.innerHTML;
            
            completeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Processing...';
            completeBtn.disabled = true;
            
            const url = `/admin/surat/${id}/complete`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            
            console.log('🌐 Making request to:', url);
            console.log('CSRF token:', csrfToken ? csrfToken.getAttribute('content') : 'Not found');
            
            // Prepare request data
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
            
            // Add CSRF token if available
            if (csrfToken) {
                requestData.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
            }
            
            console.log('📤 Request data:', requestData);
            
            fetch(url, requestData)
            .then(response => {
                console.log('📡 Response received:', {
                    status: response.status,
                    statusText: response.statusText,
                    headers: Object.fromEntries(response.headers.entries())
                });
                
                // Handle specific error status codes
                if (response.status === 419) {
                    throw new Error('CSRF_TOKEN_MISMATCH');
                }
                if (response.status === 401) {
                    throw new Error('AUTHENTICATION_REQUIRED');
                }
                if (response.status === 403) {
                    throw new Error('ACCESS_DENIED');
                }
                if (response.status === 404) {
                    throw new Error('ROUTE_NOT_FOUND');
                }
                if (response.status === 500) {
                    throw new Error('SERVER_ERROR');
                }
                
                // Try to parse JSON response
                return response.text().then(text => {
                    console.log('� Raw response:', text);
                    
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', e);
                        throw new Error('INVALID_RESPONSE_FORMAT');
                    }
                });
            })
            .then(data => {
                console.log('📦 Parsed data:', data);
                
                if (data && data.success) {
                    console.log('✅ Success! Updating UI...');
                    
                    // Show success message
                    alert('✅ Verifikasi surat berhasil diselesaikan!');
                    
                    // Reload the page to show updated data
                    location.reload();
                } else {
                    console.error('❌ Server returned error:', data);
                    const errorMessage = data && data.message ? data.message : 'Unknown error occurred';
                    alert('Gagal mengubah status: ' + errorMessage);
                    
                    // Restore button
                    completeBtn.innerHTML = originalContent;
                    completeBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('💥 Error occurred:', error);
                
                // Handle specific error types
                let errorMessage = 'Terjadi kesalahan tidak dikenal';
                
                if (error.message === 'CSRF_TOKEN_MISMATCH') {
                    errorMessage = 'Session expired. Halaman akan di-refresh.';
                    alert('🔒 ' + errorMessage);
                    location.reload();
                    return;
                } else if (error.message === 'AUTHENTICATION_REQUIRED') {
                    errorMessage = 'Anda perlu login sebagai admin terlebih dahulu.';
                    alert('🔐 ' + errorMessage);
                    window.location.href = '/login';
                    return;
                } else if (error.message === 'ACCESS_DENIED') {
                    errorMessage = 'Akses ditolak. Anda perlu role admin.';
                } else if (error.message === 'ROUTE_NOT_FOUND') {
                    errorMessage = 'Route tidak ditemukan. Hubungi administrator.';
                } else if (error.message === 'SERVER_ERROR') {
                    errorMessage = 'Server error. Coba lagi nanti.';
                } else if (error.message === 'INVALID_RESPONSE_FORMAT') {
                    errorMessage = 'Format response tidak valid dari server.';
                } else {
                    errorMessage = error.message || 'Network error occurred';
                }
                
                alert('❌ ' + errorMessage);
                
                // Restore button
                completeBtn.innerHTML = originalContent;
                completeBtn.disabled = false;
            });
        } else {
            console.log('❌ User cancelled the action');
        }
    }

    function deleteSurat(id) {
        if (confirm('Yakin ingin menghapus surat ini?')) {
            const deleteBtn = event.target.closest('button');
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...';
            deleteBtn.disabled = true;
            
            fetch(`/admin/surat/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload current filter
                    const currentFilter = select.value;
                    if (currentFilter === '') {
                        location.reload(); // Reload halaman untuk update data original
                    } else {
                        loadSuratData(currentFilter);
                    }
                } else {
                    alert('Gagal menghapus surat');
                    deleteBtn.innerHTML = '<i class="fas fa-trash mr-1"></i>Hapus';
                    deleteBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menghapus surat');
                deleteBtn.innerHTML = '<i class="fas fa-trash mr-1"></i>Hapus';
                deleteBtn.disabled = false;
            });
        }
    }

    
    // ===============================
    // EVENT LISTENERS
    // ===============================
    
    // Event listener untuk filter jenis surat
    select.addEventListener('change', function() {
        const selectedValue = this.value;
        console.log('🔄 Filter changed to:', selectedValue);
        filterSuratByJenis(selectedValue);
    });

    // Event listener untuk perPage change
    perPageSelect.addEventListener('change', function() {
        perPage = parseInt(this.value);
        currentPage = 1; // Reset to first page
        console.log('📄 Per page changed to:', perPage);
        displayCurrentPage();
        updatePaginationInfo();
    });

    // Event listener untuk pagination buttons
    prevBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    });

    nextBtn.addEventListener('click', function() {
        const totalPages = Math.ceil(totalItems / perPage);
        if (currentPage < totalPages) {
            goToPage(currentPage + 1);
        }
    });

    // Make functions global for onclick handlers
    window.deleteSurat = deleteSurat;
    window.completeSurat = completeSurat;
    
    // ===============================
    // INITIALIZATION
    // ===============================
    
    console.log('� Initializing admin surat management...');
    updateTotalCount(originalRows.length);
    displayCurrentPage();
    
    // Add success/error message handling from Laravel session
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
