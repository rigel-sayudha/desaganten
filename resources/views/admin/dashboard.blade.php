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
    <div id="adminLayout" x-data="{ sidebarOpen: false }" class="flex flex-col md:flex-row min-h-screen bg-gray-50">
        <!-- Sidebar mobile overlay -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black bg-opacity-40 md:hidden" @click="sidebarOpen = false"></div>
        <div class="md:fixed md:top-0 md:left-0 md:h-screen md:w-64 z-30">
            @include('admin.partials.sidebar')
        </div>
        <!-- Overlay for mobile sidebar -->
        <main id="adminMain" class="flex-1 ml-0 md:ml-64 pt-20 pb-8 px-2 sm:px-4 md:px-8 transition-all duration-300 w-full">
            <div class="max-w-7xl mx-auto w-full">
                <div class="mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold text-[#0088cc] mb-2">Dashboard Admin</h1>
                    <p class="text-gray-600 text-base sm:text-lg">Selamat datang di dashboard admin Desa Ganten. Kelola data desa dengan mudah dan cepat.</p>
                </div>
                @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 md:gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 flex flex-col sm:flex-row items-center hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-br from-[#0088cc] to-[#0073b1] text-white rounded-full p-4 sm:p-5 mb-2 sm:mb-0 sm:mr-4 flex items-center justify-center shadow-lg">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-2xl sm:text-3xl font-extrabold tracking-tight">5.280</div>
                            <div class="text-gray-500 font-medium">Penduduk</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 flex flex-col sm:flex-row items-center hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-br from-[#0088cc] to-[#0073b1] text-white rounded-full p-4 sm:p-5 mb-2 sm:mb-0 sm:mr-4 flex items-center justify-center shadow-lg">
                            <i class="fas fa-envelope-open-text fa-2x"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-2xl sm:text-3xl font-extrabold tracking-tight">120</div>
                            <div class="text-gray-500 font-medium">Surat Masuk</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 flex flex-col sm:flex-row items-center hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-br from-[#0088cc] to-[#0073b1] text-white rounded-full p-4 sm:p-5 mb-2 sm:mb-0 sm:mr-4 flex items-center justify-center shadow-lg">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-2xl sm:text-3xl font-extrabold tracking-tight">12</div>
                            <div class="text-gray-500 font-medium">Laporan Bulanan</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-8">
                    <h2 class="text-lg sm:text-xl font-bold mb-4 text-[#0088cc]">Statistik Penduduk</h2>
                    <div class="w-full overflow-x-auto">
                        <canvas id="statistikChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @include('admin.partials.footer')
    <script>
    // Navbar toggle for mobile
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminNavbar', () => ({
            sidebarOpen: false,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                // Sync with main layout
                const layout = document.getElementById('adminLayout');
                if(layout && layout.__x) layout.__x.$data.sidebarOpen = this.sidebarOpen;
            }
        }))
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart.js example
        const ctx = document.getElementById('statistikChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Ganten', 'Karang', 'Sumber', 'Ngasem', 'Jaten', 'Sidoharjo', 'Sumberagung'],
                datasets: [{
                    label: 'Penduduk',
                    data: [1200, 950, 800, 700, 630, 500, 500],
                    backgroundColor: '#0088cc',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
@endsection
