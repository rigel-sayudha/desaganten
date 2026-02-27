@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Verifikasi', 'url' => url('/admin/verification')],
    ['label' => 'Surat Keterangan Kehilangan', 'url' => '#'],
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
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Surat Keterangan Kehilangan</h1>
                        <p class="text-gray-600">Detail verifikasi dan tahapan proses surat keterangan kehilangan</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ url('/admin/verification') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Progress & Data -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Progress Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Progress Verifikasi</h2>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-orange-600">{{ $progress }}%</div>
                                <div class="text-sm text-gray-500">{{ $completedStages }}/{{ $totalStages }} tahap selesai</div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
                            <div class="bg-gradient-to-r from-orange-500 to-red-600 h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ $progress }}%"></div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-green-50 rounded-lg p-3">
                                <div class="text-2xl font-bold text-green-600">{{ $completedStages }}</div>
                                <div class="text-sm text-green-700">Selesai</div>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-3">
                                <div class="text-2xl font-bold text-yellow-600">{{ $inProgressStages }}</div>
                                <div class="text-sm text-yellow-700">Proses</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-2xl font-bold text-gray-600">{{ $pendingStages }}</div>
                                <div class="text-sm text-gray-700">Menunggu</div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pemohon -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-exclamation-triangle text-orange-500 mr-3"></i>
                            Data Pemohon & Kehilangan
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->nama_lengkap }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->nik }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat, Tanggal Lahir</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">
                                    {{ $surat->tempat_lahir }}, {{ $surat->tanggal_lahir ? $surat->tanggal_lahir->format('d F Y') : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->jenis_kelamin }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->pekerjaan }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->no_telepon ?? '-' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->alamat }}</div>
                            </div>
                            
                            <!-- Divider -->
                            <div class="md:col-span-2">
                                <hr class="my-4">
                                <h3 class="text-lg font-semibold text-orange-700 mb-4">Detail Kehilangan</h3>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Barang/Dokumen yang Hilang</label>
                                <div class="p-3 bg-orange-50 rounded-lg border border-orange-200">
                                    <strong class="text-orange-800">{{ $surat->barang_hilang }}</strong>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kehilangan</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">
                                    {{ $surat->tanggal_kehilangan ? $surat->tanggal_kehilangan->format('d F Y') : '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Kehilangan</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->waktu_kehilangan ?? '-' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kehilangan</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->lokasi_kehilangan }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kronologi Kehilangan</label>
                                <div class="p-3 bg-gray-50 rounded-lg border max-h-32 overflow-y-auto">{{ $surat->kronologi_kehilangan }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->keperluan }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Stages -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Status Surat</h2>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    {{ $surat->status === 'sudah diverifikasi' ? 'bg-green-100 text-green-800' : 
                                       ($surat->status === 'diproses' ? 'bg-blue-100 text-blue-800' : 
                                       ($surat->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($surat->status) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Tanggal Pengajuan:</span>
                                <span class="text-gray-900">{{ $surat->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($surat->tanggal_verifikasi_terakhir)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Terakhir Diperbarui:</span>
                                <span class="text-gray-900">{{ $surat->tanggal_verifikasi_terakhir->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Verification Stages -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Tahapan Verifikasi</h2>
                        
                        <div class="space-y-4">
                            @foreach($stages as $stageNumber => $stage)
                            <div class="border rounded-lg p-4 
                                {{ $stage['status'] === 'completed' ? 'bg-green-50 border-green-200' : 
                                   ($stage['status'] === 'in_progress' ? 'bg-blue-50 border-blue-200' : 
                                   ($stage['status'] === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200')) }}">
                                
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($stage['status'] === 'completed')
                                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-check text-white text-sm"></i>
                                                </div>
                                            @elseif($stage['status'] === 'in_progress')
                                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-clock text-white text-sm"></i>
                                                </div>
                                            @elseif($stage['status'] === 'rejected')
                                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-times text-white text-sm"></i>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 text-sm font-medium">{{ $stageNumber }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $stage['name'] }}</h3>
                                            <p class="text-sm text-gray-600">{{ $stage['description'] }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($stage['status'] !== 'completed' && $stage['status'] !== 'rejected')
                                    <div class="flex space-x-2">
                                        <button onclick="updateStage({{ $stageNumber }}, 'completed')" 
                                                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded-md transition duration-200">
                                            <i class="fas fa-check mr-1"></i>Selesai
                                        </button>
                                        <button onclick="updateStage({{ $stageNumber }}, 'rejected')" 
                                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded-md transition duration-200">
                                            <i class="fas fa-times mr-1"></i>Tolak
                                        </button>
                                    </div>
                                    @endif
                                </div>

                                @if($stage['notes'])
                                <div class="mt-3 p-3 bg-white rounded border">
                                    <p class="text-sm text-gray-700"><strong>Catatan:</strong> {{ $stage['notes'] }}</p>
                                    @if($stage['updated_at'])
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($stage['updated_at'])->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                                @endif

                                <!-- Required Documents -->
                                <div class="mt-3">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Dokumen yang diperlukan:</h4>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        @foreach($stage['required_documents'] as $doc)
                                        <li class="flex items-center">
                                            <i class="fas fa-file-alt mr-2 text-gray-400"></i>
                                            {{ $doc }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@include('admin.partials.footer')

<!-- Modal for stage update -->
<div id="stageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Update Tahapan</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan:</label>
                <textarea id="stageNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Tambahkan catatan untuk tahapan ini..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Batal
                </button>
                <button id="confirmButton" onclick="confirmUpdate()" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition">
                    Update
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentStage = null;
let currentStatus = null;

function updateStage(stageNumber, status) {
    currentStage = stageNumber;
    currentStatus = status;
    
    const modal = document.getElementById('stageModal');
    const modalTitle = document.getElementById('modalTitle');
    const confirmButton = document.getElementById('confirmButton');
    
    if (status === 'completed') {
        modalTitle.textContent = 'Selesaikan Tahapan';
        confirmButton.textContent = 'Selesaikan';
        confirmButton.className = 'px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition';
    } else if (status === 'rejected') {
        modalTitle.textContent = 'Tolak Tahapan';
        confirmButton.textContent = 'Tolak';
        confirmButton.className = 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition';
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('stageModal').classList.add('hidden');
    document.getElementById('stageNotes').value = '';
    currentStage = null;
    currentStatus = null;
}

function confirmUpdate() {
    const notes = document.getElementById('stageNotes').value;
    const confirmButton = document.getElementById('confirmButton');
    
    confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    confirmButton.disabled = true;
    
    fetch(`/admin/verification/kehilangan/{{ $surat->id }}/stage/${currentStage}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            status: currentStatus,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload untuk update tampilan
        } else {
            alert('Gagal memperbarui tahapan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal memperbarui tahapan');
    })
    .finally(() => {
        closeModal();
    });
}

// Close modal when clicking outside
document.getElementById('stageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

@endsection
