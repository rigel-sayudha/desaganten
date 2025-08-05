@extends('layouts.app')

@section('content')
    @include('admin.partials.alpinejs')
    @include('admin.partials.navbar')
    <div id="adminLayout" class="flex min-h-screen bg-gray-50">
        @include('admin.partials.sidebar')
        <main id="adminMain" class="flex-1 ml-0 md:ml-64 pt-24 pb-8 px-4 md:px-8 transition-all duration-300">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2">Dashboard Admin</h1>
                    <p class="text-gray-600">Selamat datang di dashboard admin Desa Ganten. Kelola data desa dengan mudah dan cepat.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow p-6 flex items-center">
                        <div class="bg-[#0088cc] text-white rounded-full p-4 mr-4 flex items-center justify-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold">5.280</div>
                            <div class="text-gray-500">Penduduk</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow p-6 flex items-center">
                        <div class="bg-[#0088cc] text-white rounded-full p-4 mr-4 flex items-center justify-center">
                            <i class="fas fa-envelope-open-text fa-2x"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold">120</div>
                            <div class="text-gray-500">Surat Masuk</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow p-6 flex items-center">
                        <div class="bg-[#0088cc] text-white rounded-full p-4 mr-4 flex items-center justify-center">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold">12</div>
                            <div class="text-gray-500">Laporan Bulanan</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow p-6 mb-8">
                    <h2 class="text-xl font-bold mb-4 text-[#0088cc]">Statistik Penduduk</h2>
                    <canvas id="statistikChart" height="100"></canvas>
                </div>
            </div>
        </main>
    </div>
    @include('admin.partials.footer')
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
