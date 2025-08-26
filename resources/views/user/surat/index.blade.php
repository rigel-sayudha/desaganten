@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#0088cc] to-blue-600 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Status Surat Keterangan</h1>
                    <p class="text-blue-100 mt-2">Pantau progress verifikasi surat keterangan Anda</p>
                </div>
                <div class="hidden md:block">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded-full p-1">
                            <img src="/img/logo.png" alt="Logo" class="w-full h-full rounded-full object-cover">
                        </div>
                        <div>
                            <p class="font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-blue-100">NIK: {{ Auth::user()->nik }}</p>
                        </div>
                    </div>
                </div>
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

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php
                $total = $allSurat->count();
                $diproses = $allSurat->where('status', 'diproses')->count();
                $sudahVerifikasi = $allSurat->where('status', 'sudah diverifikasi')->count();
                $ditolak = $allSurat->where('status', 'ditolak')->count();
            @endphp
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $total }}</p>
                        <p class="text-gray-600">Total Surat</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $diproses }}</p>
                        <p class="text-gray-600">Diproses</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $sudahVerifikasi }}</p>
                        <p class="text-gray-600">Sudah Diverifikasi</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $ditolak }}</p>
                        <p class="text-gray-600">Ditolak</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Surat List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-[#0088cc] to-blue-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white">Daftar Surat Keterangan</h3>
            </div>
            
            @if($allSurat->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allSurat as $surat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $jenisSuratMap = [
                                        'domisili' => 'Surat Keterangan Domisili',
                                        'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
                                        'belum_menikah' => 'Surat Keterangan Belum Menikah'
                                    ];
                                @endphp
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $jenisSuratMap[$surat->jenis_surat] ?? ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $surat->nama_pemohon }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $surat->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status = $surat->status ?? 'menunggu';
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusIcon = 'fas fa-clock';
                                    
                                    if (str_contains(strtolower($status), 'menunggu') || str_contains(strtolower($status), 'pending')) {
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        $statusIcon = 'fas fa-clock';
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                    <i class="{{ $statusIcon }} mr-1"></i>
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $progress = 0;
                                    if ($surat->tahapan_verifikasi ?? false) {
                                        $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
                                        $totalStages = count($stages);
                                        $completedStages = collect($stages)->where('status', 'completed')->count();
                                        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                                    }
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-gradient-to-r from-[#0088cc] to-blue-600 h-2 rounded-full" 
                                             style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600">{{ $progress }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('user.surat.show', [$surat->jenis_surat, $surat->id]) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                    
                                    @if($surat->status === 'sudah diverifikasi' && $progress === 100)
                                    <a href="{{ route('user.surat.print', [$surat->jenis_surat, $surat->id]) }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-xs"
                                       title="Download PDF Surat">
                                        <i class="fas fa-download mr-1"></i>PDF
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Surat</h3>
                <p class="text-gray-500">Anda belum mengajukan surat keterangan apapun.</p>
            </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Ajukan Surat Baru</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ url('/surat/domisili') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition">
                    <i class="fas fa-home text-2xl mb-2"></i>
                    <p class="font-medium">Surat Keterangan Domisili</p>
                </a>
                <a href="{{ url('/surat/belum-menikah') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition">
                    <i class="fas fa-user text-2xl mb-2"></i>
                    <p class="font-medium">Surat Keterangan Belum Menikah</p>
                </a>
                <a href="{{ url('/surat/tidak-mampu') }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg text-center transition">
                    <i class="fas fa-hand-holding-heart text-2xl mb-2"></i>
                    <p class="font-medium">Surat Keterangan Tidak Mampu</p>
                </a>
            </div>
        </div>
    </div>
</div>

@if($sudahVerifikasi > 0)
<script>
// Show notification for verified letters
document.addEventListener('DOMContentLoaded', function() {
    // Check if there are newly verified letters (this can be improved with session data)
    const verifiedCount = {{ $sudahVerifikasi }};
    if (verifiedCount > 0) {
        // Create notification for verified letters
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Anda memiliki ${verifiedCount} surat yang sudah diverifikasi!</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
});
</script>
@endif
@endsection
