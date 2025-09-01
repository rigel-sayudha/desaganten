@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Dashboard', 'url' => '#'],
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
                  // Listen for minimize state changes from sidebar
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
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Dashboard Admin</h1>
                    <p class="text-gray-600 text-center md:text-left">Selamat datang di dashboard admin Desa Ganten. Kelola data desa dengan mudah dan cepat.</p>
                </div>
                @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center justify-center hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-br from-[#0088cc] to-[#0073b1] text-white rounded-full p-5 mb-4 flex items-center justify-center shadow-lg">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-extrabold tracking-tight">{{ isset($jumlahPenduduk) ? number_format($jumlahPenduduk) : '0' }}</div>
                            <div class="text-gray-500 font-medium">Penduduk</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center justify-center hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-br from-[#0088cc] to-[#0073b1] text-white rounded-full p-5 mb-4 flex items-center justify-center shadow-lg">
                            <i class="fas fa-envelope-open-text fa-2x"></i>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-extrabold tracking-tight">{{ isset($jumlahSuratMasuk) ? number_format($jumlahSuratMasuk) : '0' }}</div>
                            <div class="text-gray-500 font-medium">Surat Masuk</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center justify-center hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-br from-[#0088cc] to-[#0073b1] text-white rounded-full p-5 mb-4 flex items-center justify-center shadow-lg">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-extrabold tracking-tight">{{ isset($jumlahWilayah) ? number_format($jumlahWilayah) : '0' }}</div>
                            <div class="text-gray-500 font-medium">Wilayah</div>
                        </div>
                    </div>
                </div>
                
                <!-- Report Generation Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-full p-3 mr-4">
                                    <i class="fas fa-file-pdf fa-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Laporan Sistem</h3>
                                    <p class="text-sm text-gray-600">Generate laporan lengkap sistem</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('report.system.view') }}" 
                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg transition duration-300 text-sm font-medium">
                                <i class="fas fa-eye mr-2"></i>Lihat Laporan
                            </a>
                            <a href="{{ route('report.system.pdf') }}" 
                               class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 px-4 rounded-lg transition duration-300 text-sm font-medium">
                                <i class="fas fa-download mr-2"></i>Download PDF
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-full p-3 mr-4">
                                    <i class="fas fa-chart-bar fa-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Status Sistem</h3>
                                    <p class="text-sm text-gray-600">Monitoring kesehatan sistem</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Database</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Online</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">PDF Generator</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Active</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">User System</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Working</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="w-full md:w-1/2">
                        <h2 class="text-xl font-bold mb-4 text-[#0088cc] text-center md:text-left">Statistik Penduduk</h2>
                        <canvas id="statistikChart" height="100"></canvas>
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col items-center justify-center">
                        <div class="bg-gradient-to-br from-[#0088cc] to-[#0073b1] text-white rounded-xl p-6 shadow-lg w-full mb-4">
                            <div class="text-lg font-bold mb-2">Info Desa</div>
                            <ul class="text-sm text-white space-y-1">
                                <li>Jumlah RT: {{ isset($infoDesa) ? $infoDesa['jumlah_rt'] : '12' }}</li>
                                <li>Jumlah RW: {{ isset($infoDesa) ? $infoDesa['jumlah_rw'] : '4' }}</li>
                                <li>Jumlah Dusun: {{ isset($infoDesa) ? $infoDesa['jumlah_dusun'] : '7' }}</li>
                                <li>Wilayah: {{ isset($infoDesa) ? $infoDesa['nama_wilayah'] : 'Ganten, Karang, Sumber, Ngasem, Jaten, Sidoharjo, Sumberagung' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @include('admin.partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart.js example
        const ctx = document.getElementById('statistikChart').getContext('2d');
        
        // Default data if not provided
        @if(isset($statistikWilayah))
            const chartLabels = {!! json_encode($statistikWilayah['labels']) !!};
            const chartData = {!! json_encode($statistikWilayah['data']) !!};
        @else
            const chartLabels = ['Ganten', 'Karang', 'Sumber', 'Ngasem', 'Jaten', 'Sidoharjo', 'Sumberagung'];
            const chartData = [0, 0, 0, 0, 0, 0, 0];
        @endif
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Penduduk',
                    data: chartData,
                    backgroundColor: '#0088cc',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
