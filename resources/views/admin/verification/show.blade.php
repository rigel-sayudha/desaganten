@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => route('admin.dashboard')],
    ['label' => 'Kelola Surat', 'url' => url('/admin/surat')],
    ['label' => 'Tahapan Verifikasi', 'url' => '#'],
];
@endphp

<!-- Clean admin layout -->
<div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-[#0088cc] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full p-1">
                        <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <h1 class="font-bold text-lg">Tahapan Verifikasi Surat</h1>
                        <p class="text-xs text-blue-200">{{ ucfirst(str_replace('_', ' ', $type)) }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/admin/surat') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumbs -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    @foreach($breadcrumbs as $key => $breadcrumb)
                        <li class="inline-flex items-center">
                            @if($key > 0)
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            @if($loop->last)
                                <span class="text-sm font-medium text-gray-500">{{ $breadcrumb['label'] }}</span>
                            @else
                                <a href="{{ $breadcrumb['url'] }}" class="text-sm font-medium text-gray-700 hover:text-[#0088cc] transition-colors">
                                    {{ $breadcrumb['label'] }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Verifikasi Surat {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </h2>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <span><i class="fas fa-user mr-2"></i>{{ $surat->nama }}</span>
                        <span><i class="fas fa-calendar mr-2"></i>{{ $surat->created_at->format('d/m/Y H:i') }}</span>
                        <span><i class="fas fa-clock mr-2"></i>Estimasi selesai: 
                            {{ $estimatedCompletion ? $estimatedCompletion->format('d/m/Y') : 'Belum ditentukan' }}
                        </span>
                    </div>
                </div>
                <div class="text-center lg:text-right">
                    <div class="mb-2">
                        <span class="text-3xl font-bold text-[#0088cc]">{{ $progress }}%</span>
                        <p class="text-sm text-gray-600">Progress Verifikasi</p>
                    </div>
                    <div class="w-full lg:w-48 bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-[#0088cc] to-blue-600 h-3 rounded-full transition-all duration-300" 
                             style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Tahapan Verifikasi -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0088cc] to-blue-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white">Tahapan Verifikasi</h3>
                    </div>
                    
                    <div class="p-6">
                        @if(is_array($stages) && count($stages) > 0)
                            @foreach($stages as $stageNumber => $stage)
                                @php
                                    // Ensure $stage is array
                                    if (!is_array($stage)) {
                                        continue; // Skip invalid stages
                                    }
                                    
                                    $stageStatus = $stage['status'] ?? 'pending';
                                    $stageName = $stage['name'] ?? 'Tahapan ' . $stageNumber;
                                    $stageDescription = $stage['description'] ?? '';
                                    $requiredDocs = $stage['required_documents'] ?? [];
                                    $durationDays = $stage['estimated_duration_days'] ?? $stage['duration_days'] ?? 1;
                                    $completedAt = $stage['completed_at'] ?? null;
                                    $verifiedBy = $stage['verified_by'] ?? null;
                                    $notes = $stage['notes'] ?? null;
                                @endphp
                                
                        <div class="relative pb-8 {{ !$loop->last ? 'border-l-2 ml-4' : '' }} 
                                    {{ $stageStatus === 'completed' ? 'border-green-300' : 
                                       ($stageStatus === 'in_progress' ? 'border-blue-300' : 'border-gray-300') }}">
                            
                            <!-- Stage Icon -->
                            <div class="absolute -left-6 mt-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold
                                            {{ $stageStatus === 'completed' ? 'bg-green-500' : 
                                               ($stageStatus === 'in_progress' ? 'bg-blue-500' : 
                                                ($stageStatus === 'rejected' ? 'bg-red-500' : 'bg-gray-400')) }}">
                                    @if($stageStatus === 'completed')
                                        <i class="fas fa-check"></i>
                                    @elseif($stageStatus === 'rejected')
                                        <i class="fas fa-times"></i>
                                    @elseif($stageStatus === 'in_progress')
                                        <i class="fas fa-clock"></i>
                                    @else
                                        {{ $stageNumber }}
                                    @endif
                                </div>
                            </div>

                            <!-- Stage Content -->
                            <div class="ml-8">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $stageName }}</h4>
                                    <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                                     {{ $stageStatus === 'completed' ? 'bg-green-100 text-green-800' : 
                                                        ($stageStatus === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                         ($stageStatus === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $stageStatus)) }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $durationDays }} hari</span>
                                    </div>
                                </div>
                                
                                @if($stageDescription)
                                <p class="text-gray-600 mb-3">{{ $stageDescription }}</p>
                                @endif
                                
                                <!-- Required Documents -->
                                @if(is_array($requiredDocs) && count($requiredDocs) > 0)
                                <div class="mb-4">
                                    <h5 class="font-medium text-gray-700 mb-2">Dokumen yang Diperlukan:</h5>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($requiredDocs as $doc)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $doc }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($completedAt)
                                <p class="text-sm text-green-600 mb-2">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Selesai: {{ \Carbon\Carbon::parse($completedAt)->format('d/m/Y H:i') }}
                                </p>
                                @endif

                                @if($verifiedBy)
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-user mr-1"></i>
                                    Diverifikasi oleh: {{ $verifiedBy }}
                                </p>
                                @endif

                                @if($notes)
                                <div class="bg-yellow-50 border-l-4 border-yellow-300 p-3 mb-4">
                                    <p class="text-sm text-yellow-800">{{ $notes }}</p>
                                </div>
                                @endif

                                <!-- Action Buttons for Current Stage -->
                                @if($stageStatus === 'in_progress')
                                <div class="flex flex-wrap gap-2 mt-4">
                                    <button onclick="completeStage({{ $stageNumber }})" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                                        <i class="fas fa-check mr-1"></i>Selesaikan
                                    </button>
                                    <button onclick="rejectStage({{ $stageNumber }})" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                        <i class="fas fa-times mr-1"></i>Tolak
                                    </button>
                                    <button onclick="addStageNote({{ $stageNumber }})" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                        <i class="fas fa-sticky-note mr-1"></i>Tambah Catatan
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-exclamation-triangle text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Data Tahapan Tidak Tersedia</h3>
                            <p class="text-gray-600 mb-4">Tahapan verifikasi belum diinisialisasi untuk surat ini.</p>
                            <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-refresh mr-2"></i>Refresh Halaman
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Status Summary -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Status</h3>
                    <div class="space-y-3">
                        @if(is_array($stages) && count($stages) > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Tahapan</span>
                            <span class="font-semibold">{{ count($stages) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Selesai</span>
                            <span class="font-semibold text-green-600">
                                {{ collect($stages)->where('status', 'completed')->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dalam Proses</span>
                            <span class="font-semibold text-blue-600">
                                {{ collect($stages)->where('status', 'in_progress')->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Menunggu</span>
                            <span class="font-semibold text-gray-600">
                                {{ collect($stages)->where('status', 'pending')->count() }}
                            </span>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">Data tahapan tidak tersedia</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Catatan Verifikasi -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Catatan Verifikasi</h3>
                    
                    <!-- Add Note Form -->
                    <div class="mb-4">
                        <textarea id="newNote" placeholder="Tambah catatan verifikasi..." 
                                  class="w-full p-3 border border-gray-300 rounded-lg resize-none" rows="3"></textarea>
                        <button onclick="addGeneralNote()" 
                                class="mt-2 bg-[#0088cc] hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm w-full">
                            <i class="fas fa-plus mr-1"></i>Tambah Catatan
                        </button>
                    </div>

                    <!-- Notes List -->
                    <div id="notesList" class="space-y-2 max-h-64 overflow-y-auto">
                        @if($surat->catatan_verifikasi)
                            @foreach(explode("\n", $surat->catatan_verifikasi) as $note)
                                @if(trim($note))
                                <div class="bg-gray-50 p-3 rounded-lg text-sm">
                                    {{ $note }}
                                </div>
                                @endif
                            @endforeach
                        @else
                            <p class="text-gray-500 text-sm">Belum ada catatan verifikasi</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modals -->
<div id="stageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 id="modalTitle" class="text-lg font-bold mb-4"></h3>
        <textarea id="modalNotes" placeholder="Tambahkan catatan..." 
                  class="w-full p-3 border border-gray-300 rounded-lg resize-none" rows="4"></textarea>
        <div class="flex gap-2 mt-4">
            <button id="modalConfirm" class="flex-1 py-2 rounded-lg text-white font-medium"></button>
            <button onclick="closeModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg font-medium">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
let currentStageAction = null;
let currentStageNumber = null;

function completeStage(stageNumber) {
    currentStageAction = 'completed';
    currentStageNumber = stageNumber;
    document.getElementById('modalTitle').textContent = 'Selesaikan Tahapan';
    document.getElementById('modalConfirm').textContent = 'Selesaikan';
    document.getElementById('modalConfirm').className = 'flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium';
    document.getElementById('stageModal').classList.remove('hidden');
}

function rejectStage(stageNumber) {
    currentStageAction = 'rejected';
    currentStageNumber = stageNumber;
    document.getElementById('modalTitle').textContent = 'Tolak Tahapan';
    document.getElementById('modalConfirm').textContent = 'Tolak';
    document.getElementById('modalConfirm').className = 'flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium';
    document.getElementById('stageModal').classList.remove('hidden');
}

function addStageNote(stageNumber) {
    currentStageAction = 'in_progress';
    currentStageNumber = stageNumber;
    document.getElementById('modalTitle').textContent = 'Tambah Catatan Tahapan';
    document.getElementById('modalConfirm').textContent = 'Simpan Catatan';
    document.getElementById('modalConfirm').className = 'flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium';
    document.getElementById('stageModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('stageModal').classList.add('hidden');
    document.getElementById('modalNotes').value = '';
    currentStageAction = null;
    currentStageNumber = null;
}

document.getElementById('modalConfirm').addEventListener('click', function() {
    const notes = document.getElementById('modalNotes').value;
    
    if (currentStageAction && currentStageNumber) {
        updateStageStatus(currentStageNumber, currentStageAction, notes);
    }
});

function updateStageStatus(stageNumber, status, notes) {
    fetch(`/admin/verification/{{ $type }}/{{ $surat->id }}/stage/${stageNumber}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            location.reload(); // Refresh to show updated status
        } else {
            alert('Gagal mengupdate tahapan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate tahapan');
    });
}

function addGeneralNote() {
    const noteText = document.getElementById('newNote').value.trim();
    
    if (!noteText) {
        alert('Silakan masukkan catatan terlebih dahulu');
        return;
    }
    
    fetch(`/admin/verification/{{ $type }}/{{ $surat->id }}/note`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            note: noteText
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('newNote').value = '';
            
            // Add note to the list
            const notesList = document.getElementById('notesList');
            const noteDiv = document.createElement('div');
            noteDiv.className = 'bg-gray-50 p-3 rounded-lg text-sm';
            noteDiv.textContent = data.note;
            notesList.appendChild(noteDiv);
        } else {
            alert('Gagal menambahkan catatan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan catatan');
    });
}
</script>
@endsection
