@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Verifikasi Surat', 'url' => url('/admin/verification')],
    ['label' => 'SKCK', 'url' => '#'],
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
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Verifikasi Surat Pengantar SKCK</h1>
                    <p class="text-gray-600">ID Surat: #{{ $surat->id }} | Pemohon: {{ $surat->nama_lengkap }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.verification.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <div class="flex items-center px-3 py-2 rounded-lg text-sm font-medium 
                        {{ $surat->status === 'diproses' ? 'bg-blue-100 text-blue-800' : 
                           ($surat->status === 'sudah diverifikasi' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ ucfirst($surat->status) }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- SKCK Information Panel -->
                <div class="lg:col-span-3 mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold mb-2">Informasi Surat Pengantar SKCK</h2>
                                <p class="text-blue-100 mb-3">Surat Keterangan Catatan Kepolisian (SKCK) adalah dokumen resmi yang diterbitkan oleh Polri untuk menerangkan tentang ada atau tidak adanya catatan kriminal seseorang.</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-yellow-300 mr-2"></i>
                                            <div>
                                                <div class="text-sm font-medium">Estimasi Proses</div>
                                                <div class="text-xs text-blue-100">7-14 hari kerja</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-check-circle text-green-300 mr-2"></i>
                                            <div>
                                                <div class="text-sm font-medium">Validitas</div>
                                                <div class="text-xs text-blue-100">6 bulan</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-building text-blue-300 mr-2"></i>
                                            <div>
                                                <div class="text-sm font-medium">Berlaku di</div>
                                                <div class="text-xs text-blue-100">Seluruh Indonesia</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden md:block">
                                <i class="fas fa-shield-alt text-6xl text-white opacity-30"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Left Column: Progress & Data -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Progress Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Progress Verifikasi</h2>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-blue-600">{{ $progress }}%</div>
                                <div class="text-sm text-gray-500">{{ $completedStages }}/{{ $totalStages }} tahap selesai</div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ $progress }}%"></div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="text-2xl font-bold text-blue-600">{{ $completedStages }}</div>
                                <div class="text-sm text-blue-700">Selesai</div>
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
                            <i class="fas fa-user-circle text-blue-500 mr-3"></i>
                            Data Pemohon
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->agama }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Perkawinan</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->status_perkawinan }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kewarganegaraan</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->kewarganegaraan }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->pekerjaan }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <div class="p-3 bg-gray-50 rounded-lg border">{{ $surat->alamat }}</div>
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
                                            @if(isset($stage['responsible_officer']))
                                                <p class="text-xs text-blue-600 mt-1">
                                                    <i class="fas fa-user-tie mr-1"></i>
                                                    Penanggung Jawab: {{ $stage['responsible_officer'] }}
                                                </p>
                                            @endif
                                            @if(isset($stage['duration_days']))
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Estimasi: {{ $stage['duration_days'] }} hari kerja
                                                </p>
                                            @endif
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

                                @if($stage['notes'] && isset($stage['notes']))
                                <div class="mt-3 p-3 bg-white rounded border">
                                    <p class="text-sm text-gray-700"><strong>Catatan:</strong> {{ $stage['notes'] }}</p>
                                    @if(isset($stage['updated_at']) && $stage['updated_at'])
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($stage['updated_at'])->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                                @endif

                                <!-- Required Documents -->
                                <div class="mt-3">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Dokumen yang diperlukan:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($stage['required_documents'] as $doc)
                                        <div class="flex items-center text-xs text-gray-600 bg-white p-2 rounded border">
                                            <i class="fas fa-file-alt mr-2 text-gray-400"></i>
                                            {{ $doc }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Progress Indicator for this stage -->
                                @if($stage['status'] === 'completed')
                                    <div class="mt-3 flex items-center text-xs text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Tahap ini telah selesai
                                    </div>
                                @elseif($stage['status'] === 'in_progress')
                                    <div class="mt-3 flex items-center text-xs text-blue-600">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        Sedang dalam proses verifikasi
                                    </div>
                                @elseif($stage['status'] === 'rejected')
                                    <div class="mt-3 flex items-center text-xs text-red-600">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Tahap ini ditolak - silakan hubungi petugas
                                    </div>
                                @else
                                    <div class="mt-3 flex items-center text-xs text-gray-500">
                                        <i class="fas fa-clock mr-1"></i>
                                        Menunggu tahap sebelumnya selesai
                                    </div>
                                @endif
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
                <textarea id="stageNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tambahkan catatan untuk tahapan ini..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Batal
                </button>
                <button id="confirmButton" onclick="confirmUpdate()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
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
        modalTitle.textContent = `Selesaikan Tahapan ${stageNumber}`;
        confirmButton.textContent = 'Selesaikan';
        confirmButton.className = 'px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition transform hover:scale-105';
    } else if (status === 'rejected') {
        modalTitle.textContent = `Tolak Tahapan ${stageNumber}`;
        confirmButton.textContent = 'Tolak';
        confirmButton.className = 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition transform hover:scale-105';
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('fade-in');
    
    // Focus on textarea for better UX
    setTimeout(() => {
        document.getElementById('stageNotes').focus();
    }, 100);
}

function closeModal() {
    const modal = document.getElementById('stageModal');
    modal.classList.add('fade-out');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('fade-in', 'fade-out');
        document.getElementById('stageNotes').value = '';
        currentStage = null;
        currentStatus = null;
    }, 200);
}

function confirmUpdate() {
    const notes = document.getElementById('stageNotes').value;
    const confirmButton = document.getElementById('confirmButton');
    
    // Validate notes for rejected status
    if (currentStatus === 'rejected' && !notes.trim()) {
        showNotification('Catatan wajib diisi untuk penolakan tahapan', 'error');
        return;
    }
    
    confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    confirmButton.disabled = true;
    
    fetch(`/admin/verification/skck/{{ $surat->id }}/stage/${currentStage}`, {
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
            showNotification(
                `Tahapan ${currentStage} berhasil ${currentStatus === 'completed' ? 'diselesaikan' : 'ditolak'}`, 
                'success'
            );
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification('Gagal memperbarui tahapan: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal memperbarui tahapan', 'error');
    })
    .finally(() => {
        closeModal();
    });
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existing = document.querySelector('.notification');
    if (existing) existing.remove();
    
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 px-6 py-3 rounded-md shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' :
                'fa-info-circle'
            } mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Close modal when clicking outside
document.getElementById('stageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('stageModal').classList.contains('hidden')) {
        closeModal();
    }
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    .fade-in {
        animation: fadeIn 0.2s ease-out;
    }
    
    .fade-out {
        animation: fadeOut 0.2s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.95); }
    }
    
    .notification {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>

@endsection
