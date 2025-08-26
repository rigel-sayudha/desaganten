@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Statistik Penduduk', 'url' => '#'],
    ['label' => 'Wilayah', 'url' => '#'],
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
            <div class="max-w-7xl mx-auto w-full">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#0088cc] mb-2 text-center md:text-left">Kelola Data Wilayah</h1>
                    <p class="text-gray-600 text-center md:text-left">Kelola data statistik penduduk berdasarkan wilayah</p>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                    <div class="md:w-1/2">
                        <span id="totalWilayah" class="text-sm text-gray-600 font-medium">
                            Total: {{ count($wilayah) }} data wilayah
                        </span>
                    </div>
                    <div class="md:w-1/2 text-right">
                        <button onclick="openWilayahModal()" 
                                class="bg-[#0088cc] text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>Tambah Data Wilayah
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <table class="min-w-full w-full bg-white">
                            <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold">No</th>
                                    <th class="px-6 py-4 text-left font-semibold">Nama/Kelompok/Jenis</th>
                                    <th class="px-6 py-4 text-center font-semibold">Jumlah Data</th>
                                    <th class="px-6 py-4 text-center font-semibold">Laki-laki</th>
                                    <th class="px-6 py-4 text-center font-semibold">Perempuan</th>
                                    <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($wilayah as $index => $w)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-6 text-center font-medium">{{ $index + 1 }}</td>
                                    <td class="py-3 px-6 font-semibold text-gray-700">{{ $w->nama }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ number_format($w->jumlah) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ number_format($w->laki_laki) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                            {{ number_format($w->perempuan) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex gap-2 justify-center">
                                            <button onclick="editWilayah({{ $w->id }})" 
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded transition">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                            <form action="{{ route('admin.wilayah.destroy', $w->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition"
                                                        onclick="return confirm('Yakin ingin menghapus data wilayah ini?')">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                        <p class="text-lg">Belum ada data wilayah</p>
                                        <p class="text-sm">Klik tombol "Tambah Data Wilayah" untuk menambah data</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
@include('admin.partials.footer')
@endsection
