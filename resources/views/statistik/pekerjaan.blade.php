@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <section class="pt-24 pb-16 bg-gray-50 min-h-screen mt-0">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h1 class="text-3xl md:text-4xl font-bold text-[#0088cc] mb-4 md:mb-0 text-center md:text-left">Data Pekerjaan & Statistik Penduduk</h1>
                <!-- Breadcrumbs -->
                <nav class="flex items-center space-x-2 text-sm bg-white rounded-lg shadow px-4 py-2" aria-label="Breadcrumb">
                    <a href="/" class="text-[#0088cc] hover:text-sky-600 flex items-center">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="/statistik/pekerjaan" class="text-[#0088cc] hover:text-sky-600">Statistik</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-500 font-semibold">Pekerjaan</span>
                </nav>
            </div>
            <div class="mt-4"></div>
            <div class="overflow-x-auto rounded-lg shadow-lg bg-white">
                <table class="min-w-full divide-y divide-blue-200">
                    <thead class="bg-[#0088cc]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Jenis Pekerjaan</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Jumlah Data</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">L</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">P</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-100">
                        @forelse($pekerjaan as $p)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-700">{{ $p->nama }}</td>
                            <td class="px-6 py-4 text-center text-gray-700">{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center text-blue-700 font-bold">{{ number_format($p->laki_laki, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center text-pink-700 font-bold">{{ number_format($p->perempuan, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-6 text-gray-400">Belum ada data pekerjaan.</td></tr>
                        @endforelse
                        @if($pekerjaan->count())
                        <tr class="bg-blue-100 font-bold">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">Total</td>
                            <td class="px-6 py-4 text-center text-gray-900">{{ number_format($pekerjaan->sum('jumlah'), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center text-blue-900">{{ number_format($pekerjaan->sum('laki_laki'), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center text-pink-900">{{ number_format($pekerjaan->sum('perempuan'), 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-8 text-center text-gray-500 text-sm">* Data di atas merupakan data penduduk per tahun 2025</div>
        </div>
    </section>

    @include('partials.footer')
@endsection
