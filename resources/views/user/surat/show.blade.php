@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#0088cc] to-blue-600 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Detail {{ $jenisSurat }}</h1>
                    <p class="text-blue-100 mt-1">Pantau progress verifikasi surat Anda</p>
                </div>
                <a href="{{ route('user.surat.index') }}" 
                   class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Notifications -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
        @endif

        @if(str_contains(strtolower($surat->status ?? ''), 'selesai diproses'))
        <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-double mr-3 text-lg"></i>
                    <div>
                        <div class="font-semibold">{{ $jenisSurat }} Sudah Selesai Diproses!</div>
                        <div class="text-sm text-emerald-700">Dokumen Anda telah selesai diproses dan siap untuk diunduh dalam format PDF.</div>
                    </div>
                </div>
                <a href="{{ route('user.surat.print', [$type, $surat->id]) }}" 
                   class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-download mr-2"></i>Download PDF
                </a>
            </div>
        </div>
        @endif

        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $jenisSurat }}</h2>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <span><i class="fas fa-user mr-2"></i>
                            @if(isset($surat->nama_lengkap))
                                {{ $surat->nama_lengkap }}
                            @elseif(isset($surat->nama))
                                {{ $surat->nama }}
                            @elseif(isset($surat->nama_pelapor))
                                {{ $surat->nama_pelapor }}
                            @else
                                Tidak diketahui
                            @endif
                        </span>
                        <span><i class="fas fa-id-card mr-2"></i>NIK: 
                            @if(isset($surat->nik))
                                {{ $surat->nik }}
                            @elseif(isset($surat->nik_pelapor))
                                {{ $surat->nik_pelapor }}
                            @else
                                Tidak diketahui
                            @endif
                        </span>
                        <span><i class="fas fa-calendar mr-2"></i>{{ $surat->created_at->format('d/m/Y H:i') }}</span>
                        @if($surat->tanggal_verifikasi_terakhir)
                        <span><i class="fas fa-clock mr-2"></i>Update terakhir: {{ \Carbon\Carbon::parse($surat->tanggal_verifikasi_terakhir)->format('d/m/Y H:i') }}</span>
                        @endif
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
                    @if($progress === 100 || str_contains(strtolower($surat->status ?? ''), 'selesai diproses'))
                    <div class="mt-2">
                        <a href="{{ route('user.surat.print', [$type, $surat->id]) }}" 
                           class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </a>
                    </div>
                    @endif
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
                        @if(count($stages) > 0)
                        @foreach($stages as $stageNumber => $stage)
                        <div class="relative pb-8 {{ !$loop->last ? 'border-l-2 ml-4' : '' }} 
                                    {{ ($stage['status'] ?? 'pending') === 'completed' ? 'border-green-300' : 
                                       (($stage['status'] ?? 'pending') === 'in_progress' ? 'border-blue-300' : 'border-gray-300') }}">>
                            
                            <!-- Stage Icon -->
                            <div class="absolute -left-6 mt-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold
                                            {{ ($stage['status'] ?? 'pending') === 'completed' ? 'bg-green-500' : 
                                               (($stage['status'] ?? 'pending') === 'in_progress' ? 'bg-blue-500' : 
                                                (($stage['status'] ?? 'pending') === 'rejected' ? 'bg-red-500' : 'bg-gray-400')) }}">
                                    @if(($stage['status'] ?? 'pending') === 'completed')
                                        <i class="fas fa-check"></i>
                                    @elseif(($stage['status'] ?? 'pending') === 'rejected')
                                        <i class="fas fa-times"></i>
                                    @elseif(($stage['status'] ?? 'pending') === 'in_progress')
                                        <i class="fas fa-clock"></i>
                                    @else
                                        {{ $stageNumber }}
                                    @endif
                                </div>
                            </div>

                            <!-- Stage Content -->
                            <div class="ml-8">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $stage['name'] ?? 'Tahapan ' . $stageNumber }}</h4>
                                    <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                                     {{ ($stage['status'] ?? 'pending') === 'completed' ? 'bg-green-100 text-green-800' : 
                                                        (($stage['status'] ?? 'pending') === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                         (($stage['status'] ?? 'pending') === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $stage['status'] ?? 'pending')) }}
                                        </span>
                                        @if(isset($stage['duration_days']))
                                        <span class="text-xs text-gray-500">{{ $stage['duration_days'] }} hari</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-3">{{ $stage['description'] ?? 'Tidak ada deskripsi' }}</p>
                                
                                <!-- Required Documents -->
                                @if(isset($stage['required_documents']) && is_array($stage['required_documents']) && count($stage['required_documents']) > 0)
                                <div class="mb-4">
                                    <h5 class="font-medium text-gray-700 mb-2">Dokumen yang Diperlukan:</h5>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($stage['required_documents'] as $doc)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $doc }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if(isset($stage['completed_at']) && $stage['completed_at'])
                                <p class="text-sm text-green-600 mb-2">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Selesai: {{ \Carbon\Carbon::parse($stage['completed_at'])->format('d/m/Y H:i') }}
                                </p>
                                @endif

                                @if(isset($stage['verified_by']) && $stage['verified_by'])
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-user mr-1"></i>
                                    Diverifikasi oleh: {{ $stage['verified_by'] }}
                                </p>
                                @endif

                                @if(isset($stage['notes']) && $stage['notes'])
                                <div class="bg-yellow-50 border-l-4 border-yellow-300 p-3 mb-4">
                                    <p class="text-sm text-yellow-800">{{ $stage['notes'] }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center py-8">
                            <i class="fas fa-info-circle text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Tahapan verifikasi belum dimulai</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Status Summary -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Status Surat</h3>
                    <div class="text-center mb-4">
                        @php
                            $status = $surat->status ?? 'menunggu';
                            $statusClass = 'bg-gray-100 text-gray-800';
                            $statusIcon = 'fas fa-clock';
                            
                            if (str_contains(strtolower($status), 'menunggu') || str_contains(strtolower($status), 'pending')) {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusIcon = 'fas fa-clock';
                            } elseif (str_contains(strtolower($status), 'selesai diproses')) {
                                $statusClass = 'bg-emerald-100 text-emerald-800';
                                $statusIcon = 'fas fa-check-double';
                            } elseif (str_contains(strtolower($status), 'approved') || str_contains(strtolower($status), 'disetujui')) {
                                $statusClass = 'bg-emerald-100 text-emerald-800';
                                $statusIcon = 'fas fa-check-double';
                            } elseif (str_contains(strtolower($status), 'sudah diverifikasi') || str_contains(strtolower($status), 'selesai')) {
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusIcon = 'fas fa-check-circle';
                            } elseif (str_contains(strtolower($status), 'diproses')) {
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusIcon = 'fas fa-cog fa-spin';
                            } elseif (str_contains(strtolower($status), 'ditolak')) {
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusIcon = 'fas fa-times-circle';
                            }
                        @endphp
                        <div class="inline-flex items-center px-4 py-2 rounded-full {{ $statusClass }} text-sm font-medium mb-2">
                            <i class="{{ $statusIcon }} mr-2"></i>
                            {{ ucfirst($status) }}
                        </div>
                    </div>
                    
                    @if(count($stages) > 0)
                    <div class="space-y-3">
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
                    </div>
                    @endif
                </div>

                <!-- Dokumen Upload -->
                @php
                    $fileColumns = [];
                    $fileLabels = [];
                    
                    // Define file columns and labels based on surat type
                    switch($type) {
                        case 'domisili':
                            $fileColumns = ['file_ktp', 'file_kk', 'file_pengantar_rt', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP', 'Scan KK', 'Surat Pengantar RT', 'Dokumen Tambahan'];
                            break;
                        case 'surat_ktp':
                            $fileColumns = ['file_ktp', 'file_kk', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP', 'Scan KK', 'Dokumen Tambahan'];
                            break;
                        case 'surat_kk':
                            $fileColumns = ['file_ktp', 'file_kk_lama', 'file_surat_pindah', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP', 'Scan KK Lama', 'Surat Pindah', 'Dokumen Tambahan'];
                            break;
                        case 'surat_kelahiran':
                            $fileColumns = ['file_ktp_ayah', 'file_ktp_ibu', 'file_kk', 'file_surat_nikah', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP Ayah', 'Scan KTP Ibu', 'Scan KK', 'Surat Nikah', 'Dokumen Tambahan'];
                            break;
                        case 'surat_kematian':
                            $fileColumns = ['file_ktp_pelapor', 'file_kk', 'file_surat_dokter', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP Pelapor', 'Scan KK', 'Surat Keterangan Dokter', 'Dokumen Tambahan'];
                            break;
                        case 'surat_skck':
                            $fileColumns = ['file_ktp', 'file_kk', 'file_pas_foto', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP', 'Scan KK', 'Pas Foto', 'Dokumen Tambahan'];
                            break;
                        case 'surat_usaha':
                            $fileColumns = ['file_ktp', 'file_kk', 'file_foto_usaha', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP', 'Scan KK', 'Foto Usaha', 'Dokumen Tambahan'];
                            break;
                        case 'surat_kehilangan':
                            $fileColumns = ['file_ktp_pelapor', 'file_kk', 'file_laporan_polisi', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP Pelapor', 'Scan KK', 'Laporan Polisi', 'Dokumen Tambahan'];
                            break;
                        case 'belum_menikah':
                            $fileColumns = ['file_ktp', 'file_kk', 'file_pas_foto', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP', 'Scan KK', 'Pas Foto', 'Dokumen Tambahan'];
                            break;
                        case 'tidak_mampu':
                            $fileColumns = ['file_ktp', 'file_kk', 'file_slip_gaji', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP', 'Scan KK', 'Slip Gaji', 'Dokumen Tambahan'];
                            break;
                        default:
                            $fileColumns = ['file_ktp', 'file_kk', 'file_dokumen_tambahan'];
                            $fileLabels = ['Scan KTP', 'Scan KK', 'Dokumen Tambahan'];
                    }
                    
                    // Check which files are actually uploaded
                    $uploadedFiles = [];
                    foreach($fileColumns as $index => $column) {
                        if(isset($surat->$column) && $surat->$column) {
                            $uploadedFiles[] = [
                                'column' => $column,
                                'label' => $fileLabels[$index],
                                'path' => $surat->$column
                            ];
                        }
                    }
                @endphp

                @if(count($uploadedFiles) > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-file-image mr-2 text-blue-600"></i>Dokumen Upload
                    </h3>
                    <div class="space-y-4">
                        @foreach($uploadedFiles as $file)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-800">{{ $file['label'] }}</h4>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                    <i class="fas fa-check mr-1"></i>Terupload
                                </span>
                            </div>
                            
                            <!-- Image Preview -->
                            <div class="mb-3">
                                @php
                                    $fileExtension = strtolower(pathinfo($file['path'], PATHINFO_EXTENSION));
                                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                
                                @if($isImage)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $file['path']) }}" 
                                             alt="{{ $file['label'] }}" 
                                             class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity"
                                             onclick="openImageModal('{{ asset('storage/' . $file['path']) }}', '{{ $file['label'] }}')">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            <i class="fas fa-search-plus text-white text-xl"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center h-32 bg-gray-100 rounded-lg border border-gray-200">
                                        <div class="text-center">
                                            <i class="fas fa-file-alt text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-600">{{ strtoupper($fileExtension) }} File</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                @if($isImage)
                                <button onclick="openImageModal('{{ asset('storage/' . $file['path']) }}', '{{ $file['label'] }}')" 
                                        class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </button>
                                @endif
                                <a href="{{ asset('storage/' . $file['path']) }}" 
                                   download
                                   class="flex-1 bg-green-50 hover:bg-green-100 text-green-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Catatan Verifikasi -->
                @if($surat->catatan_verifikasi)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Catatan Verifikasi</h3>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach(explode("\n", $surat->catatan_verifikasi) as $note)
                            @if(trim($note))
                            <div class="bg-gray-50 p-3 rounded-lg text-sm">
                                {{ $note }}
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Action Button -->
                @if($progress === 100 || str_contains(strtolower($surat->status ?? ''), 'selesai diproses'))
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white text-center">
                    <i class="fas fa-check-circle text-3xl mb-3"></i>
                    <h3 class="text-lg font-bold mb-2">
                        @if($surat->status === 'approved')
                            Surat Sudah Disetujui!
                        @elseif(str_contains(strtolower($surat->status ?? ''), 'selesai diproses'))
                            Surat Selesai Diproses!
                        @else
                            Surat Sudah Diverifikasi!
                        @endif
                    </h3>
                    <p class="text-green-100 mb-4 text-sm">
                        @if(str_contains(strtolower($surat->status ?? ''), 'selesai diproses'))
                            Surat Anda telah selesai diproses dan siap untuk diunduh.
                        @else
                            Surat Anda telah selesai diverifikasi dan siap untuk diunduh.
                        @endif
                    </p>
                    <a href="{{ route('user.surat.print', [$type, $surat->id]) }}" 
                       class="inline-flex items-center bg-white text-green-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-download mr-2"></i>Download PDF
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Floating Action Button for Download PDF when Ready -->
    @if(str_contains(strtolower($surat->status ?? ''), 'selesai diproses') && $progress < 100)
    <div class="fixed bottom-6 right-6 z-50">
        <a href="{{ route('user.surat.print', [$type, $surat->id]) }}" 
           class="group bg-emerald-500 hover:bg-emerald-600 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center">
                <i class="fas fa-download text-xl"></i>
                <span class="ml-2 text-sm font-medium hidden group-hover:inline-block transition-all duration-300">Download PDF</span>
            </div>
        </a>
    </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        <div class="absolute bottom-4 left-4 bg-black bg-opacity-60 text-white px-4 py-2 rounded-lg">
            <span id="modalImageTitle"></span>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc, title) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalImageTitle');
    
    modalImage.src = imageSrc;
    modalImage.alt = title;
    modalTitle.textContent = title;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

@endsection
