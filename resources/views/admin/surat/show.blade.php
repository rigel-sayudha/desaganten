@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Pengelolaan Surat', 'url' => url('/admin/surat')],
    ['label' => 'Detail Surat', 'url' => '#'],
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
              document.addEventListener('sidebar-minimized', (e) => {
                  sidebarMinimized = e.detail.minimized;
              });
          "
    >
        <div class="max-w-4xl mx-auto w-full">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-file-alt text-[#0088cc] mr-3"></i>
                            Detail Surat
                        </h1>
                        <div class="flex items-center space-x-3">
                            <a href="{{ url('/admin/surat') }}" 
                               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            @if($surat)
                                <a href="{{ route('surat.edit', $surat->id) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                                    <i class="fas fa-edit mr-2"></i>Edit Surat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($surat)
                <!-- Surat Details Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Surat</h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $jenisSurat[$jenis] ?? ucfirst($jenis) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-md font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                    Informasi Pemohon
                                </h3>
                                
                                @foreach($surat->toArray() as $key => $value)
                                    @if(!in_array($key, ['id', 'created_at', 'updated_at', 'status', 'status_pengajuan', 'tahapan_verifikasi']))
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="text-gray-600 font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                            <span class="text-gray-900">{{ $value ?? '-' }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Status Information -->
                            <div class="space-y-4">
                                <h3 class="text-md font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                    Status & Informasi
                                </h3>
                                
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600 font-medium">Tanggal Pengajuan:</span>
                                    <span class="text-gray-900">{{ $surat->created_at->format('d F Y, H:i') }}</span>
                                </div>
                                
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600 font-medium">Status:</span>
                                    @php
                                        $status = $surat->status_pengajuan ?? $surat->status ?? 'Tidak diketahui';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if(str_contains(strtolower($status), 'menunggu') || str_contains(strtolower($status), 'pending'))
                                            bg-yellow-100 text-yellow-800
                                        @elseif(str_contains(strtolower($status), 'sudah diverifikasi') || str_contains(strtolower($status), 'selesai'))
                                            bg-green-100 text-green-800
                                        @elseif(str_contains(strtolower($status), 'diproses'))
                                            bg-blue-100 text-blue-800
                                        @elseif(str_contains(strtolower($status), 'ditolak'))
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ $status }}
                                    </span>
                                </div>

                                @if($surat->updated_at != $surat->created_at)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-medium">Terakhir Diupdate:</span>
                                        <span class="text-gray-900">{{ $surat->updated_at->format('d F Y, H:i') }}</span>
                                    </div>
                                @endif

                                <!-- Progress Bar for certain surat types -->
                                @if(in_array($jenis, ['domisili', 'tidak_mampu', 'belum_menikah']) && isset($surat->tahapan_verifikasi))
                                    @php
                                        $stages = is_string($surat->tahapan_verifikasi) ? json_decode($surat->tahapan_verifikasi, true) : $surat->tahapan_verifikasi;
                                        $totalStages = count($stages ?? []);
                                        $completedStages = collect($stages ?? [])->where('status', 'completed')->count();
                                        $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                                    @endphp
                                    
                                    <div class="py-2">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-gray-600 font-medium">Progress Verifikasi:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-[#0088cc] to-blue-600 h-2 rounded-full" 
                                                 style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                ID Surat: #{{ $surat->id }}
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ url('/admin/verification/' . $jenis . '/' . $surat->id) }}" 
                                   class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200 font-medium">
                                    <i class="fas fa-tasks mr-2"></i>Verifikasi
                                </a>
                                <a href="/admin/surat/print-pdf/{{ strtolower($jenis) }}/{{ $surat->id }}" target="_blank" 
                                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium">
                                    <i class="fas fa-print mr-2"></i>Print PDF
                                </a>
                                <form action="{{ route('surat.destroy', $surat->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium"
                                            onclick="return confirm('Yakin ingin menghapus surat ini?')">
                                        <i class="fas fa-trash mr-2"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Data Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                    <i class="fas fa-file-alt text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Surat Tidak Ditemukan</h3>
                    <p class="text-gray-600 mb-4">Surat yang Anda cari tidak ditemukan atau telah dihapus.</p>
                    <a href="{{ url('/admin/surat') }}" 
                       class="px-6 py-3 bg-[#0088cc] text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Surat
                    </a>
                </div>
            @endif
        </div>
    </main>
</div>
@include('admin.partials.footer')
@endsection
