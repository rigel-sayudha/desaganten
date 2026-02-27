@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Verifikasi Surat', 'url' => '#'],
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
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Verifikasi Surat</h1>
                <p class="text-gray-600">Kelola dan verifikasi semua surat keterangan yang sedang diproses</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Total Processing -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Sedang Diproses</p>
                            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $totalPending }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-cogs text-blue-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Surat Domisili -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Surat Domisili</p>
                            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $totalDomisili }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-home text-blue-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Surat Tidak Mampu -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Surat Tidak Mampu</p>
                            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ $totalTidakMampu }}</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-full">
                            <i class="fas fa-hand-holding-heart text-red-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Surat Belum Menikah -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Surat Belum Menikah</p>
                            <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $totalBelumMenikah }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-user text-green-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics Cards Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Surat SKCK -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Surat SKCK</p>
                            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ $totalSkck ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-shield-alt text-purple-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Surat KTP -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Surat KTP</p>
                            <p class="text-2xl sm:text-3xl font-bold text-indigo-600">{{ $totalKtp ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 rounded-full">
                            <i class="fas fa-id-card text-indigo-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Surat KK -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Surat KK</p>
                            <p class="text-2xl sm:text-3xl font-bold text-yellow-600">{{ $totalKk ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-users text-yellow-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Surat Usaha -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Surat Usaha</p>
                            <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ $totalUsaha ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-full">
                            <i class="fas fa-briefcase text-orange-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Surat Domisili Section -->
            @if($domisiliSurat->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 sm:mb-8">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-home text-blue-600 mr-3"></i>
                        Surat Domisili - Dalam Proses
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($domisiliSurat as $surat)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $surat->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $surat->nik }}</div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $surat->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($surat->status === 'diproses' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($surat->status) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @php
                                        $progress = 0;
                                        if (!empty($surat->tahapan_verifikasi)) {
                                            $completedStages = collect($surat->tahapan_verifikasi)->where('completed', true)->count();
                                            $totalStages = count($surat->tahapan_verifikasi);
                                            $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                                        }
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.verification.domisili', $surat->id) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-tasks mr-1"></i>Verifikasi Detail
                                        </a>
                                        <a href="{{ route('admin.verification.show', ['type' => 'domisili', 'id' => $surat->id]) }}" 
                                           class="text-[#0088cc] hover:text-blue-700 font-medium">
                                            Verifikasi Umum
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Surat Tidak Mampu Section -->
            @if($tidakMampuSurat->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 sm:mb-8">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-hand-holding-heart text-red-600 mr-3"></i>
                        Surat Tidak Mampu - Dalam Proses
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tidakMampuSurat as $surat)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $surat->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $surat->nik }}</div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $surat->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($surat->status === 'diproses' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($surat->status) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @php
                                        $progress = 0;
                                        if (!empty($surat->tahapan_verifikasi)) {
                                            $completedStages = collect($surat->tahapan_verifikasi)->where('completed', true)->count();
                                            $totalStages = count($surat->tahapan_verifikasi);
                                            $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                                        }
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.verification.show', ['type' => 'tidak_mampu', 'id' => $surat->id]) }}" 
                                       class="text-[#0088cc] hover:text-blue-700 font-medium">
                                        Verifikasi
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Surat Belum Menikah Section -->
            @if($belumMenikahSurat->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 sm:mb-8">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user text-green-600 mr-3"></i>
                        Surat Belum Menikah - Dalam Proses
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($belumMenikahSurat as $surat)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $surat->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $surat->nik }}</div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $surat->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($surat->status === 'diproses' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($surat->status) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @php
                                        $progress = 0;
                                        if (!empty($surat->tahapan_verifikasi)) {
                                            $completedStages = collect($surat->tahapan_verifikasi)->where('completed', true)->count();
                                            $totalStages = count($surat->tahapan_verifikasi);
                                            $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                                        }
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.verification.show', ['type' => 'belum_menikah', 'id' => $surat->id]) }}" 
                                       class="text-[#0088cc] hover:text-blue-700 font-medium">
                                        Verifikasi
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Surat SKCK Section -->
            @if(isset($skckSurat) && $skckSurat->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 sm:mb-8">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-shield-alt text-purple-600 mr-3"></i>
                        Surat Pengantar SKCK - Dalam Proses
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keperluan</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($skckSurat as $surat)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $surat->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ $surat->nik }}</div>
                                        <div class="text-xs text-gray-400">{{ $surat->tempat_lahir }}, {{ \Carbon\Carbon::parse($surat->tanggal_lahir)->format('d M Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $surat->keperluan }}">
                                        {{ $surat->keperluan }}
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $surat->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($surat->status === 'diproses' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($surat->status) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    @php
                                        $progress = 0;
                                        if (!empty($surat->tahapan_verifikasi)) {
                                            $completedStages = 0;
                                            $totalStages = count($surat->tahapan_verifikasi);
                                            
                                            foreach($surat->tahapan_verifikasi as $stage) {
                                                if (isset($stage['status']) && in_array($stage['status'], ['completed', 'approved'])) {
                                                    $completedStages++;
                                                }
                                            }
                                            
                                            $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                                        }
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-600">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.verification.skck', $surat->id) }}" 
                                           class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                            <i class="fas fa-tasks mr-1"></i>Verifikasi Detail
                                        </a>
                                        <a href="{{ route('admin.verification.show', ['type' => 'skck', 'id' => $surat->id]) }}" 
                                           class="text-[#0088cc] hover:text-blue-700 font-medium">
                                            Verifikasi Umum
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Empty State -->
            @if($totalPending == 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sm:p-12 text-center">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-check-circle text-green-500 text-5xl sm:text-6xl mb-4"></i>
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-2">Semua Surat Sudah Diverifikasi</h3>
                    <p class="text-gray-600 mb-6">Tidak ada surat yang sedang diproses saat ini. Semua surat telah berhasil diverifikasi.</p>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#0088cc] text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
