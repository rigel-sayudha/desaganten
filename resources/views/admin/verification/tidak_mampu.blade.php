@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Verifikasi Surat', 'url' => url('/admin/verification')],
    ['label' => 'Tidak Mampu', 'url' => '#'],
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
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Verifikasi Surat Keterangan Tidak Mampu</h1>
                    <p class="text-gray-600">ID Surat: #{{ $surat->id }} | Pemohon: {{ $surat->nama }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.verification.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <div class="flex items-center px-3 py-2 rounded-lg text-sm font-medium 
                        {{ $surat->status === 'diproses' ? 'bg-blue-100 text-blue-800' : 
                           ($surat->status === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ ucfirst($surat->status) }}
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

                        <!-- Verification Stages -->
                        <div class="space-y-4">
                            @if(is_array($stages) && count($stages) > 0)
                                @foreach($stages as $stageNumber => $stage)
                                    <div class="border rounded-lg p-4 transition-all duration-300
                                        {{ $stage['status'] === 'completed' ? 'bg-green-50 border-green-200' : 
                                           ($stage['status'] === 'in_progress' ? 'bg-blue-50 border-blue-200' : 
                                           ($stage['status'] === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200')) }}">
                                        
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold
                                                    {{ $stage['status'] === 'completed' ? 'bg-green-500' : 
                                                       ($stage['status'] === 'in_progress' ? 'bg-blue-500' : 
                                                       ($stage['status'] === 'rejected' ? 'bg-red-500' : 'bg-gray-400')) }}">
                                                    @if($stage['status'] === 'completed')
                                                        <i class="fas fa-check"></i>
                                                    @elseif($stage['status'] === 'in_progress')
                                                        <i class="fas fa-clock"></i>
                                                    @elseif($stage['status'] === 'rejected')
                                                        <i class="fas fa-times"></i>
                                                    @else
                                                        {{ $stageNumber }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold text-gray-900">{{ $stage['name'] ?? 'Tahap ' . $stageNumber }}</h3>
                                                    @if(isset($stage['description']))
                                                        <p class="text-sm text-gray-600">{{ $stage['description'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if($stage['status'] !== 'completed' && $stage['status'] !== 'rejected')
                                                    <button type="button" 
                                                            onclick="updateStageStatus({{ $stageNumber }}, 'completed')"
                                                            class="px-3 py-1 bg-green-500 text-white text-xs rounded-lg hover:bg-green-600 transition-colors">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Selesai
                                                    </button>
                                                    <button type="button" 
                                                            onclick="updateStageStatus({{ $stageNumber }}, 'rejected')"
                                                            class="px-3 py-1 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition-colors">
                                                        <i class="fas fa-times mr-1"></i>
                                                        Tolak
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        @if(isset($stage['notes']) && $stage['notes'])
                                            <div class="mt-2 p-3 bg-white rounded border-l-4 border-blue-500">
                                                <p class="text-sm text-gray-700">{{ $stage['notes'] }}</p>
                                            </div>
                                        @endif

                                        @if(isset($stage['updated_at']) && $stage['updated_at'])
                                            <div class="mt-2 text-xs text-gray-500">
                                                Diperbarui: {{ \Carbon\Carbon::parse($stage['updated_at'])->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-info-circle text-4xl mb-4"></i>
                                    <p>Belum ada tahapan verifikasi</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Data Pemohon -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Data Pemohon</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <div class="text-gray-900 font-semibold">{{ $surat->nama }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                <div class="text-gray-900">{{ $surat->nik }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat, Tanggal Lahir</label>
                                <div class="text-gray-900">{{ $surat->tempat_lahir }}, {{ \Carbon\Carbon::parse($surat->tanggal_lahir)->format('d F Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <div class="text-gray-900">{{ $surat->jenis_kelamin }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                                <div class="text-gray-900">{{ $surat->agama }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                                <div class="text-gray-900">{{ $surat->pekerjaan }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan</label>
                                <div class="text-gray-900">{{ $surat->penghasilan }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tanggungan</label>
                                <div class="text-gray-900">{{ $surat->jumlah_tanggungan }} orang</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Rumah</label>
                                <div class="text-gray-900">{{ $surat->status_rumah }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <div class="text-gray-900">{{ $surat->alamat }}</div>
                            </div>
                        </div>

                        <!-- Keterangan Ekonomi -->
                        <hr class="my-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Ekonomi</label>
                            <div class="text-gray-900">{{ $surat->keterangan_ekonomi }}</div>
                        </div>

                        <!-- Keperluan -->
                        <hr class="my-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan</label>
                            <div class="text-gray-900">{{ $surat->keperluan }}</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Actions & Notes -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h2>
                        
                        <div class="space-y-3">
                            <button type="button" 
                                    onclick="approveAll()"
                                    class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium">
                                <i class="fas fa-check-double mr-2"></i>
                                Setujui Semua Tahapan
                            </button>
                            
                            <button type="button" 
                                    onclick="rejectSurat()"
                                    class="w-full px-4 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium">
                                <i class="fas fa-times mr-2"></i>
                                Tolak Surat
                            </button>
                        </div>
                    </div>

                    <!-- Add Note -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Tambah Catatan</h2>
                        
                        <form onsubmit="addNote(event)">
                            <textarea name="note" 
                                      rows="4" 
                                      placeholder="Tulis catatan verifikasi..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"
                                      required></textarea>
                            <button type="submit" 
                                    class="mt-3 w-full px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors font-medium">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Catatan
                            </button>
                        </form>
                    </div>

                    <!-- Existing Notes -->
                    @if($surat->catatan_verifikasi)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Catatan Verifikasi</h2>
                        
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @foreach(explode("\n", $surat->catatan_verifikasi) as $note)
                                @if(trim($note))
                                    <div class="p-3 bg-gray-50 rounded-lg border-l-4 border-orange-500">
                                        <p class="text-sm text-gray-700">{{ trim($note) }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Submission Info -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Pengajuan</h2>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Pengajuan:</span>
                                <span class="text-gray-900 font-medium">{{ $surat->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Terakhir Diperbarui:</span>
                                <span class="text-gray-900 font-medium">{{ $surat->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($surat->tanggal_verifikasi_terakhir)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Terakhir Diverifikasi:</span>
                                <span class="text-gray-900 font-medium">{{ $surat->tanggal_verifikasi_terakhir->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function updateStageStatus(stageNumber, status) {
    const notes = status === 'rejected' ? prompt('Masukkan alasan penolakan:') : prompt('Catatan (opsional):');
    
    if (status === 'rejected' && !notes) {
        alert('Alasan penolakan harus diisi');
        return;
    }

    fetch(`/admin/verification/tidak_mampu/{{ $surat->id }}/stage/${stageNumber}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            notes: notes || ''
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate tahapan');
    });
}

function approveAll() {
    if (confirm('Apakah Anda yakin ingin menyetujui semua tahapan verifikasi?')) {
        // Implement approve all logic
        alert('Fitur ini akan segera tersedia');
    }
}

function rejectSurat() {
    const reason = prompt('Masukkan alasan penolakan surat:');
    if (reason) {
        // Implement reject surat logic
        alert('Fitur ini akan segera tersedia');
    }
}

function addNote(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(`/admin/verification/tidak_mampu/{{ $surat->id }}/note`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambah catatan');
    });
}
</script>
@endsection
