@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Statistik', 'url' => route('admin.statistik.index')],
    ['label' => 'Data Pekerjaan', 'url' => '#'],
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
            
            <!-- Flash Messages -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Kelola Data Pekerjaan</h1>
                    <p class="text-gray-600 text-center md:text-left">Kelola data statistik pekerjaan penduduk Desa Ganten</p>
                </div>
                @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                
                <div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Kelola Data Pekerjaan</h1>
                <p class="text-gray-600">Kelola data statistik pekerjaan penduduk Desa Ganten</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('admin.statistik.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
                <a href="{{ route('admin.statistik.pekerjaan.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Data Pekerjaan
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Population Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Penduduk -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Total Penduduk</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($totalPenduduk ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Laki-laki -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-male text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Laki-laki</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalLakiLaki ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Perempuan -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-pink-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-female text-pink-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Perempuan</p>
                    <p class="text-2xl font-bold text-pink-600">{{ number_format($totalPerempuan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Kepala Keluarga -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-home text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Kepala Keluarga</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($totalKK ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-briefcase text-blue-600 mr-2"></i>
                Daftar Data Pekerjaan
            </h3>
        </div>
        
        @if($data->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pekerjaan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Laki-laki</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Perempuan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data as $index => $pekerjaan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $pekerjaan->nama_pekerjaan }}</div>
                            @if($pekerjaan->keterangan)
                            <div class="text-sm text-gray-500">{{ $pekerjaan->keterangan }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                            {{ number_format($pekerjaan->laki_laki, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                            {{ number_format($pekerjaan->perempuan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ number_format($pekerjaan->jumlah, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.statistik.pekerjaan.edit', $pekerjaan) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.statistik.pekerjaan.destroy', $pekerjaan) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100">
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-sm font-medium text-gray-900">Total Keseluruhan</td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-900">
                            {{ number_format($data->sum('laki_laki'), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-900">
                            {{ number_format($data->sum('perempuan'), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-900">
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ number_format($data->sum('jumlah'), 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-briefcase text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data pekerjaan</h3>
            <p class="text-gray-600 mb-6">Mulai dengan menambahkan data pekerjaan pertama</p>
            <a href="{{ route('admin.statistik.pekerjaan.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Data Pekerjaan
            </a>
        </div>
        @endif
    </div>

    <!-- Information Panel -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-blue-900 mb-2">Informasi</h3>
                <div class="text-blue-800 space-y-2">
                    <p>• Data pekerjaan yang diinput akan otomatis ditampilkan di halaman statistik publik</p>
                    <p>• Total jumlah dihitung otomatis dari jumlah laki-laki + perempuan</p>
                    <p>• Data yang dihapus akan disembunyikan dari tampilan publik (soft delete)</p>
                    <p>• Lihat hasil di: <a href="/statistik" target="_blank" class="underline">Halaman Statistik Publik</a></p>
                </div>
            </div>
        </div>
                </div>
            </div>
        </main>
    </div>
@endsection
