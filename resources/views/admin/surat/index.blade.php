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
                                <th class="px-4 py-4 text-center font-semibold">Progress</th>
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
                                            $status = $surat->status ?? '-';
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            if (str_contains(strtolower($status), 'menunggu') || str_contains(strtolower($status), 'pending')) {
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                            } elseif (str_contains(strtolower($status), 'sudah diverifikasi') || str_contains(strtolower($status), 'selesai')) {
                                                $statusClass = 'bg-green-100 text-green-800';
                                            } elseif (str_contains(strtolower($status), 'diproses')) {
                                                $statusClass = 'bg-blue-100 text-blue-800';
                                            } elseif (str_contains(strtolower($status), 'ditolak')) {
                                                $statusClass = 'bg-red-100 text-red-800';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if(in_array($surat->jenis_surat, ['domisili', 'tidak_mampu', 'belum_menikah']))
                                            @php
                                                $progress = 0;
                                                if ($surat->tahapan_verifikasi ?? false) {
                                                    $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
                                                    $totalStages = count($stages);
                                                    $completedStages = collect($stages)->where('status', 'completed')->count();
                                                    $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                                                }
                                            @endphp
                                            <div class="flex items-center justify-center space-x-2">
                                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-[#0088cc] to-blue-600 h-2 rounded-full" 
                                                         style="width: {{ $progress }}%"></div>
                                                </div>
                                                <span class="text-xs font-medium text-gray-600">{{ $progress }}%</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex gap-1 justify-center flex-wrap">
                                            <!-- Tombol Verifikasi -->
                                            <a href="{{ url('/admin/verification/' . $surat->jenis_surat . '/' . $surat->id) }}" 
                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded transition" 
                                               title="Lihat Tahapan Verifikasi">
                                                <i class="fas fa-tasks mr-1"></i>Verifikasi
                                            </a>
                                            
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('admin.surat.edit', $surat->id) }}" 
                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition"
                                               title="Edit Surat">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            
                                            <!-- Tombol Detail/Lihat -->
                                            <a href="{{ route('admin.surat.show', $surat->id) }}" 
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
                                            <form action="{{ route('admin.surat.destroy', $surat->id) }}" method="POST" class="inline">
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
            </div>
        </div>
    </main>
</div>
@include('admin.partials.footer')
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('jenisSuratSelect');
    const tbody = document.getElementById('suratTableBody');
    const totalSpan = document.getElementById('totalSurat');
    
    // Store original data untuk filtering
    const originalRows = Array.from(tbody.querySelectorAll('tr'));
    
    function updateTotalCount(count) {
        totalSpan.textContent = `Total: ${count} surat`;
    }

    function filterSuratByJenis(jenis = '') {
        // Hide semua rows dulu
        originalRows.forEach(row => row.style.display = 'none');
        
        let visibleCount = 0;
        
        if (jenis === '') {
            // Show semua rows jika tidak ada filter
            originalRows.forEach(row => {
                row.style.display = '';
                visibleCount++;
            });
        } else {
            // Show hanya rows yang sesuai dengan jenis
            originalRows.forEach(row => {
                const rowJenis = row.getAttribute('data-jenis');
                if (rowJenis === jenis) {
                    row.style.display = '';
                    visibleCount++;
                }
            });
        }
        
        // Update nomor urut
        updateRowNumbers();
        updateTotalCount(visibleCount);
        
        // Show pesan jika tidak ada data
        if (visibleCount === 0) {
            showNoDataMessage(jenis);
        } else {
            hideNoDataMessage();
        }
    }
    
    function updateRowNumbers() {
        const visibleRows = Array.from(tbody.querySelectorAll('tr')).filter(row => 
            row.style.display !== 'none' && !row.classList.contains('no-data-row')
        );
        
        visibleRows.forEach((row, index) => {
            const firstCell = row.querySelector('td:first-child');
            if (firstCell && !row.classList.contains('no-data-row')) {
                firstCell.textContent = index + 1;
            }
        });
    }
    
    function showNoDataMessage(jenis) {
        hideNoDataMessage(); // Remove existing message first
        
        const noDataRow = document.createElement('tr');
        noDataRow.className = 'no-data-row border-b';
        noDataRow.innerHTML = `
            <td colspan="6" class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                <p class="text-lg">Tidak ada data surat ${getJenisSuratDisplay(jenis)}</p>
            </td>
        `;
        tbody.appendChild(noDataRow);
    }
    
    function hideNoDataMessage() {
        const noDataRows = tbody.querySelectorAll('.no-data-row');
        noDataRows.forEach(row => row.remove());
    }

    function loadSuratData(jenis = '') {
        if (jenis === '') {
            // Jika tidak ada filter, gunakan data yang sudah ada di halaman
            filterSuratByJenis('');
            return;
        }
        
        // Show loading state
        tbody.innerHTML = '<tr class="loading-row"><td colspan="6" class="text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat data...</td></tr>';
        
        const url = `/admin/surat/data?jenis=${jenis}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                displaySuratData(data, jenis);
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-500"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat data.</td></tr>';
            });
    }

    function displaySuratData(data, jenis) {
        tbody.innerHTML = '';
        
        if (!data || data.length === 0) {
            showNoDataMessage(jenis);
            updateTotalCount(0);
            return;
        }

        data.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'border-b hover:bg-gray-50 transition-colors';
            row.setAttribute('data-jenis', jenis);
            
            const nama = item.nama || item.nama_pemohon || item.nama_lengkap || '-';
            const status = item.status_pengajuan || item.status || '-';
            const tanggal = formatDate(item.created_at || item.tanggal_pengajuan);
            const jenisSuratDisplay = getJenisSuratDisplay(jenis);
            
            // Status badge styling
            const statusClass = getStatusClass(status);
            
            row.innerHTML = `
                <td class="py-3 px-4 text-center font-medium">${index + 1}</td>
                <td class="py-3 px-4">${nama}</td>
                <td class="py-3 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ${jenisSuratDisplay}
                    </span>
                </td>
                <td class="py-3 px-4">${tanggal}</td>
                <td class="py-3 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                        ${status}
                    </span>
                </td>
                <td class="py-3 px-4">
                    <div class="flex gap-1 flex-wrap">
                        <a href="/admin/verification/${jenis}/${item.id}" 
                           class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded transition" 
                           title="Lihat Tahapan Verifikasi">
                            <i class="fas fa-tasks mr-1"></i>Verifikasi
                        </a>
                        <a href="/admin/surat/${item.id}/edit" 
                           class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition"
                           title="Edit Surat">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <a href="/admin/surat/${item.id}" 
                           class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded transition"
                           title="Lihat Detail Surat">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </a>
                        <a href="/admin/surat/print-pdf/${jenis.toLowerCase()}/${item.id}" target="_blank" 
                           class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition" 
                           title="Print PDF">
                            <i class="fas fa-print mr-1"></i>Print
                        </a>
                        <button onclick="deleteSurat(${item.id})" 
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition"
                                title="Hapus Surat">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
        
        updateTotalCount(data.length);
    }
    
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID');
    }
    
    function getStatusClass(status) {
        const statusLower = status.toLowerCase();
        if (statusLower.includes('menunggu') || statusLower.includes('pending')) {
            return 'bg-yellow-100 text-yellow-800';
        } else if (statusLower.includes('disetujui') || statusLower.includes('selesai')) {
            return 'bg-green-100 text-green-800';
        } else if (statusLower.includes('ditolak')) {
            return 'bg-red-100 text-red-800';
        }
        return 'bg-gray-100 text-gray-800';
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
        return jenisSuratMap[jenis] || jenis.toUpperCase();
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

    function verifikasiSurat(id, jenis) {
        // Modal untuk verifikasi surat
        const statusOptions = [
            { value: 'Menunggu Verifikasi', text: 'Menunggu Verifikasi' },
            { value: 'Sedang Diproses', text: 'Sedang Diproses' },
            { value: 'Sudah Diverifikasi', text: 'Sudah Diverifikasi' },
            { value: 'Ditolak', text: 'Ditolak' }
        ];

        let optionsHtml = '';
        statusOptions.forEach(option => {
            optionsHtml += `<option value="${option.value}">${option.text}</option>`;
        });

        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
        modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Verifikasi Surat</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-3">Ubah status verifikasi untuk surat ${jenis.replace('_', ' ').toUpperCase()}:</p>
                        <select id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional):</label>
                        <textarea id="catatanVerifikasi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Tambahkan catatan verifikasi..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                            Batal
                        </button>
                        <button onclick="updateVerifikasi(${id}, '${jenis}')" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            <i class="fas fa-check mr-2"></i>Update Status
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        // Function to close modal
        window.closeModal = function() {
            document.body.removeChild(modal);
        };

        // Function to update verification
        window.updateVerifikasi = function(suratId, jenisSurat) {
            const status = document.getElementById('statusSelect').value;
            const catatan = document.getElementById('catatanVerifikasi').value;
            
            const updateBtn = event.target;
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
            updateBtn.disabled = true;

            fetch(`/admin/surat/${suratId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: status,
                    catatan: catatan,
                    jenis_surat: jenisSurat
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    // Reload current filter to show updated status
                    const currentFilter = select.value;
                    if (currentFilter === '') {
                        location.reload();
                    } else {
                        loadSuratData(currentFilter);
                    }
                    alert('Status verifikasi berhasil diperbarui');
                } else {
                    alert('Gagal memperbarui status: ' + (data.message || 'Unknown error'));
                    updateBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Update Status';
                    updateBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memperbarui status verifikasi');
                updateBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Update Status';
                updateBtn.disabled = false;
            });
        };
    }

    // Event listener untuk select change
    select.addEventListener('change', function() {
        const selectedValue = this.value;
        
        if (selectedValue === '') {
            // Jika pilih "Semua Jenis Surat", tampilkan semua data original
            filterSuratByJenis('');
        } else {
            // Load data spesifik untuk jenis tertentu
            loadSuratData(selectedValue);
        }
    });

    // Make functions global
    window.deleteSurat = deleteSurat;
    window.verifikasiSurat = verifikasiSurat;
    
    // Initialize dengan menampilkan semua data
    updateTotalCount(originalRows.length);
});
</script>
@endpush
