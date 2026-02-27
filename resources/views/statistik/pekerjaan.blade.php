@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <section class="pt-24 pb-16 bg-gray-50 min-h-screen mt-0">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-[#0088cc] mb-4 md:mb-0 text-center md:text-left">
                    Data Pekerjaan Desa Ganten
                </h1>
                <!-- Breadcrumbs -->
                <nav class="flex items-center space-x-2 text-sm bg-white rounded-lg shadow px-4 py-2" aria-label="Breadcrumb">
                    <a href="/" class="text-[#0088cc] hover:text-sky-600 flex items-center">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('statistik.main') }}" class="text-[#0088cc] hover:text-sky-600">Statistik</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-500 font-semibold">Data Pekerjaan</span>
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
                                <a href="{{ route('statistik.umur') }}" class="sidebar-menu-item block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#0088cc] transition-colors border-l-4 border-transparent hover:border-[#0088cc]">
                                    <i class="fas fa-birthday-cake mr-2 text-sm"></i>
                                    Umur (Rentang)
                                </a>
                                <a href="{{ route('statistik.pendidikan') }}" class="sidebar-menu-item block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#0088cc] transition-colors border-l-4 border-transparent hover:border-[#0088cc]">
                                    <i class="fas fa-graduation-cap mr-2 text-sm"></i>
                                    Pendidikan Dalam KK
                                </a>
                                <a href="{{ route('statistik.pekerjaan') }}" class="sidebar-menu-item block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#0088cc] transition-colors border-l-4 border-[#0088cc] bg-gray-100 text-[#0088cc] font-medium">
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
                    <!-- Statistics Cards -->
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
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-600">Kepala Keluarga</h3>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalKK, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-full">
                                    <i class="fas fa-home text-yellow-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Active Section: Pekerjaan -->
                    <div class="mb-6">
                        <div class="bg-white rounded-lg shadow-sm border-l-4 border-[#0088cc] p-4 mb-4">
                            <h2 class="text-lg font-bold text-gray-900">
                                <i class="fas fa-briefcase text-[#0088cc] mr-2"></i>
                                Data Penduduk Menurut Pekerjaan
                            </h2>
                            <nav class="text-sm text-gray-600 mt-1">
                                <a href="/" class="text-[#0088cc] hover:underline">Beranda</a>
                                <span class="mx-1">></span>
                                <a href="{{ route('statistik.main') }}" class="text-[#0088cc] hover:underline">Statistik</a>
                                <span class="mx-1">></span>
                                <span>Data Pekerjaan</span>
                            </nav>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="bg-white rounded-lg shadow-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900">
                                <i class="fas fa-table text-[#0088cc] mr-2"></i>
                                Tabel Data Pekerjaan Penduduk
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-[#0088cc]">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Jenis Pekerjaan</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Jumlah Data</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Laki-laki</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Perempuan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($pekerjaan as $p)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-700">{{ $p->nama }}</td>
                                        <td class="px-6 py-4 text-center text-gray-700">{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center text-blue-700 font-bold">{{ number_format($p->laki_laki, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center text-pink-700 font-bold">{{ number_format($p->perempuan, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-6 text-gray-400">Belum ada data pekerjaan.</td></tr>
                                    @endforelse
                                    @if($pekerjaan->count())
                                    <tr class="bg-gray-100 font-bold">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">Total</td>
                                        <td class="px-6 py-4 text-center text-gray-900">{{ number_format($pekerjaan->sum('jumlah'), 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center text-blue-900">{{ number_format($pekerjaan->sum('laki_laki'), 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center text-pink-900">{{ number_format($pekerjaan->sum('perempuan'), 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="mt-8 text-center text-gray-500 text-sm bg-white rounded-lg shadow p-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        * Data di atas merupakan data penduduk per tahun 2025 yang diperoleh dari database kependudukan Desa Ganten
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script>
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
