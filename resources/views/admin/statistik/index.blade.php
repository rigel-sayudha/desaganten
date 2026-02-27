@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Statistik', 'url' => '#'],
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
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Dashboard Statistik Penduduk</h1>
                    <p class="text-gray-600 text-center md:text-left">Kelola data statistik penduduk Desa Ganten</p>
                </div>
                @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                
                <div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Dashboard Statistik Penduduk</h1>
                <p class="text-gray-600">Kelola data statistik penduduk Desa Ganten</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ date('d F Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Data Pekerjaan -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Data Pekerjaan</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalPekerjaan }}</p>
                    <p class="text-sm text-gray-600">Jenis pekerjaan tersedia</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.statistik.pekerjaan') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Kelola Data
                </a>
            </div>
        </div>

        <!-- Data Umur -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-birthday-cake text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Data Kelompok Umur</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $totalUmur }}</p>
                    <p class="text-sm text-gray-600">Kelompok umur tersedia</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.statistik.umur') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Kelola Data
                </a>
            </div>
        </div>

        <!-- Data Pendidikan -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Data Pendidikan</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $totalPendidikan }}</p>
                    <p class="text-sm text-gray-600">Tingkat pendidikan tersedia</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.statistik.pendidikan') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Kelola Data
                </a>
            </div>
        </div>

        <!-- Data Wilayah -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Data Wilayah</h3>
                    <p class="text-3xl font-bold text-orange-600">{{ $totalWilayah }}</p>
                    <p class="text-sm text-gray-600">Wilayah tersedia</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.statistik.wilayah.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Kelola Data
                </a>
            </div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                Aksi Cepat
            </h3>
            <div class="space-y-3">
                <a href="{{ route('admin.statistik.pekerjaan.create') }}" 
                   class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    <span class="font-medium text-blue-700">Tambah Data Pekerjaan</span>
                </a>
                <a href="{{ route('admin.statistik.umur.create') }}" 
                   class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <i class="fas fa-plus-circle text-green-600 mr-3"></i>
                    <span class="font-medium text-green-700">Tambah Data Umur</span>
                </a>
                <a href="{{ route('admin.statistik.pendidikan.create') }}" 
                   class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <i class="fas fa-plus-circle text-purple-600 mr-3"></i>
                    <span class="font-medium text-purple-700">Tambah Data Pendidikan</span>
                </a>
                <a href="{{ route('admin.statistik.wilayah.create') }}" 
                   class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                    <i class="fas fa-plus-circle text-orange-600 mr-3"></i>
                    <span class="font-medium text-orange-700">Tambah Data Wilayah</span>
                </a>
            </div>
        </div>

        <!-- Public View Links -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-eye text-blue-500 mr-2"></i>
                Lihat Halaman Publik
            </h3>
            <div class="space-y-3">
                <a href="/statistik" target="_blank"
                   class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-external-link-alt text-gray-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-700">Halaman Statistik Utama</div>
                        <div class="text-sm text-gray-500">Data pekerjaan penduduk</div>
                    </div>
                </a>
                <a href="/statistik/umur" target="_blank"
                   class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-external-link-alt text-gray-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-700">Halaman Data Umur</div>
                        <div class="text-sm text-gray-500">Statistik kelompok umur</div>
                    </div>
                </a>
                <a href="/statistik/pendidikan" target="_blank"
                   class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-external-link-alt text-gray-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-700">Halaman Data Pendidikan</div>
                        <div class="text-sm text-gray-500">Statistik tingkat pendidikan</div>
                    </div>
                </a>
                <a href="/statistik/wilayah" target="_blank"
                   class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-external-link-alt text-gray-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-700">Halaman Data Wilayah</div>
                        <div class="text-sm text-gray-500">Statistik wilayah penduduk</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

@endsection
