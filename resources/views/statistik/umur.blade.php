@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <section class="pt-24 pb-16 bg-gray-50 min-h-screen mt-0">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-[#0088cc] mb-4 md:mb-0 text-center md:text-left">
                    Data Kelompok Umur Desa Ganten
                </h1>
                <!-- Breadcrumbs -->
                <nav class="flex items-center space-x-2 text-sm bg-white rounded-lg shadow px-4 py-2" aria-label="Breadcrumb">
                    <a href="/" class="text-[#0088cc] hover:text-sky-600 flex items-center">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('statistik.main') }}" class="text-[#0088cc] hover:text-sky-600">Statistik</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-500 font-semibold">Data Kelompok Umur</span>
                </nav>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Mobile Menu Toggle -->
                <div class="lg:hidden">
                    <button id="sidebarToggle" class="w-full bg-[#0088cc] text-white px-4 py-3 rounded-lg mb-4 flex items-center justify-center">
                        <i class="fas fa-bars mr-2"></i>
                        Menu Kategori Statistik
                    </button>
                </div>

                <!-- Sidebar Menu -->
                <div class="lg:w-1/4">
                    <div id="sidebarMenu" class="bg-white rounded-lg shadow-lg hidden lg:block">
                        <!-- Statistik Penduduk Section -->
                        <div>
                            <div class="bg-[#0088cc] text-white px-4 py-3 rounded-t-lg">
                                <h3 class="font-bold flex items-center">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Statistik Penduduk
                                </h3>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('statistik.umur') }}" class="sidebar-menu-item block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#0088cc] transition-colors border-l-4 border-[#0088cc] bg-gray-100 text-[#0088cc] font-medium">
                                    <i class="fas fa-birthday-cake mr-2 text-sm"></i>
                                    Umur (Rentang)
                                </a>
                                <a href="{{ route('statistik.pendidikan') }}" class="sidebar-menu-item block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#0088cc] transition-colors border-l-4 border-transparent hover:border-[#0088cc]">
                                    <i class="fas fa-graduation-cap mr-2 text-sm"></i>
                                    Pendidikan Dalam KK
                                </a>
                                <a href="{{ route('statistik.main') }}" class="sidebar-menu-item block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#0088cc] transition-colors border-l-4 border-transparent hover:border-[#0088cc]">
                                    <i class="fas fa-briefcase mr-2 text-sm"></i>
                                    Pekerjaan
                                </a>
                                <a href="{{ route('statistik.wilayah') }}" class="sidebar-menu-item block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#0088cc] transition-colors border-l-4 border-transparent hover:border-[#0088cc]">
                                    <i class="fas fa-map-marker-alt mr-2 text-sm"></i>
                                    Wilayah
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Main Content Area -->
                <div class="lg:w-3/4">


                    <!-- Current Active Section: Umur -->
                    <div class="mb-6">
                        <div class="bg-white rounded-lg shadow-sm border-l-4 border-[#0088cc] p-4 mb-4">
                            <h2 class="text-lg font-bold text-gray-900">
                                <i class="fas fa-birthday-cake text-[#0088cc] mr-2"></i>
                                Data Penduduk Menurut Kelompok Umur
                            </h2>
                            <nav class="text-sm text-gray-600 mt-1">
                                <a href="/" class="text-[#0088cc] hover:underline">Beranda</a>
                                <span class="mx-1">></span>
                                <a href="{{ route('statistik.main') }}" class="text-[#0088cc] hover:underline">Statistik</a>
                                <span class="mx-1">></span>
                                <span>Data Kelompok Umur</span>
                            </nav>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-600">Total Penduduk</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPenduduk, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class="fas fa-users text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-600">Laki-laki</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalLakiLaki, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fas fa-male text-green-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-pink-500">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-600">Perempuan</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPerempuan, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-pink-100 p-3 rounded-full">
                                    <i class="fas fa-female text-pink-600"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-600">Kelompok Umur</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ count($dataUmur) }}</p>
                                </div>
                                <div class="bg-orange-100 p-3 rounded-full">
                                    <i class="fas fa-birthday-cake text-orange-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Section - Umur -->
                    <div id="umur-section" class="bg-white rounded-lg shadow-lg p-6 mb-8">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4 sm:mb-0">
                                <i class="fas fa-birthday-cake text-[#0088cc] mr-2"></i>
                                Grafik Kelompok Umur
                            </h2>
                            <div class="flex space-x-2">
                                <button 
                                    onclick="showChart('bar')" 
                                    id="barBtn"
                                    class="px-4 py-2 bg-[#0088cc] text-white rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium"
                                >
                                    <i class="fas fa-chart-bar mr-1"></i> Bar Graph
                                </button>
                                <button 
                                    onclick="showChart('pie')" 
                                    id="pieBtn"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors text-sm font-medium"
                                >
                                    <i class="fas fa-chart-pie mr-1"></i> Pie Graph
                                </button>
                            </div>
                        </div>
                        
                        <div class="relative" style="height: 400px;">
                            <canvas id="umurChart"></canvas>
                        </div>
                        
                        <!-- Chart Legend for mobile -->
                        <div class="mt-4 text-sm text-gray-600 text-center">
                            <p><i class="fas fa-info-circle mr-1"></i> Klik tombol di atas untuk mengubah jenis grafik</p>
                        </div>
                    </div>

                    <!-- Tabel Kelompok Umur - Full Width -->
                    <div class="bg-white rounded-lg shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">
                                <i class="fas fa-table text-[#0088cc] mr-2"></i>
                                Tabel Detail Kelompok Umur
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok Umur</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Laki-laki</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Perempuan</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($dataUmur as $index => $umur)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $umur['kelompok'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-blue-600 font-semibold">{{ number_format($umur['laki_laki'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-pink-600 font-semibold">{{ number_format($umur['perempuan'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-900">{{ number_format($umur['jumlah'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-purple-600 font-semibold">
                                            {{ number_format(($umur['jumlah'] / $totalPenduduk) * 100, 1) }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-gray-100 font-bold">
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Total</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-blue-800">{{ number_format(collect($dataUmur)->sum('laki_laki'), 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-pink-800">{{ number_format(collect($dataUmur)->sum('perempuan'), 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">{{ number_format(collect($dataUmur)->sum('jumlah'), 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-purple-800">100.0%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="mt-8 text-center text-gray-500 text-sm bg-white rounded-lg shadow p-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        * Data kelompok umur di atas merupakan data penduduk per tahun 2025 yang diperoleh dari database kependudukan Desa Ganten
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <!-- Chart.js CDN with 3D effects -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-3d@1.0.3/dist/chartjs-plugin-3d.min.js"></script>
    
    <script>
        let umurChart;
        
        // Data umur
        const umurData = {
            labels: [
                @foreach($dataUmur as $umur)
                '{{ $umur["kelompok"] }}',
                @endforeach
            ],
            datasets: [{
                label: 'Jumlah Penduduk',
                data: [
                    @foreach($dataUmur as $umur)
                    {{ $umur["jumlah"] }},
                    @endforeach
                ],
                backgroundColor: [
                    '#0088cc', '#28a745', '#dc3545', '#ffc107', '#6f42c1',
                    '#fd7e14', '#20c997', '#e83e8c', '#6c757d', '#17a2b8',
                    '#495057', '#343a40', '#007bff', '#6610f2', '#e91e63',
                    '#ff5722', '#9c27b0', '#4caf50'
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        };

        function showChart(type) {
            const ctx = document.getElementById('umurChart').getContext('2d');
            
            // Destroy existing chart
            if (umurChart) {
                umurChart.destroy();
            }
            
            // Update button states
            document.getElementById('barBtn').className = type === 'bar' 
                ? 'px-4 py-2 bg-[#0088cc] text-white rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium'
                : 'px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors text-sm font-medium';
            
            document.getElementById('pieBtn').className = type === 'pie' 
                ? 'px-4 py-2 bg-[#0088cc] text-white rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium'
                : 'px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors text-sm font-medium';
            
            // Chart configuration
            const config = {
                type: type,
                data: umurData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: type === 'pie' ? 'bottom' : 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 11
                                },
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#0088cc',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || context.parsed.y || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value.toLocaleString('id-ID')} orang (${percentage}%)`;
                                }
                            }
                        }
                    },
                    scales: type === 'bar' ? {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('id-ID');
                                },
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    } : {},
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            };
            
            // Create new chart
            umurChart = new Chart(ctx, config);
        }
        
        // Initialize with bar chart
        document.addEventListener('DOMContentLoaded', function() {
            showChart('bar');
        });

        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebarMenu = document.getElementById('sidebarMenu');
            const toggleBtn = this;
            
            if (sidebarMenu.classList.contains('hidden')) {
                sidebarMenu.classList.remove('hidden');
                toggleBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Tutup Menu';
            } else {
                sidebarMenu.classList.add('hidden');
                toggleBtn.innerHTML = '<i class="fas fa-bars mr-2"></i>Menu Kategori Statistik';
            }
        });

        // Auto hide mobile menu when clicking menu items
        document.querySelectorAll('.sidebar-menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    const sidebarMenu = document.getElementById('sidebarMenu');
                    const toggleBtn = document.getElementById('sidebarToggle');
                    sidebarMenu.classList.add('hidden');
                    toggleBtn.innerHTML = '<i class="fas fa-bars mr-2"></i>Menu Kategori Statistik';
                }
            });
        });
    </script>
@endsection
