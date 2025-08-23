@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Wilayah', 'url' => '#'],
];
@endphp
@include('admin.partials.alpinejs')
@include('admin.partials.navbar')
<div id="adminLayout" class="min-h-screen bg-gray-50 relative">
    @include('admin.partials.sidebar')
    <main id="adminMain" class="flex-1 pt-24 pb-8 px-2 sm:px-4 md:px-8 transition-all duration-300" style="padding-left: 0; padding-left: 16rem;">
        <div class="max-w-7xl mx-auto w-full">
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h1 class="text-2xl font-bold text-[#0088cc] text-center md:text-left">Kelola Data Wilayah</h1>
                <button onclick="openWilayahModal()" class="bg-[#0088cc] text-white px-4 py-2 rounded hover:bg-[#006fa1] font-semibold w-full md:w-auto">Tambah Data Wilayah</button>
            </div>
            @if(session('success'))
                <div class="mb-4 text-green-600 text-center">{{ session('success') }}</div>
            @endif
            <div class="overflow-x-auto bg-white rounded-2xl shadow-lg min-h-[400px] flex flex-col justify-between">
                <table class="min-w-full divide-y divide-blue-200">
                    <thead class="bg-[#0088cc]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nama/Kelompok/Jenis</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Jumlah Data</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">L</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">P</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-100 min-h-[320px]">
                        @forelse($wilayah as $w)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-700 align-middle">{{ $w->nama }}</td>
                            <td class="px-6 py-4 text-center text-gray-700 align-middle">{{ $w->jumlah }}</td>
                            <td class="px-6 py-4 text-center text-blue-700 font-bold align-middle">{{ $w->laki_laki }}</td>
                            <td class="px-6 py-4 text-center text-pink-700 font-bold align-middle">{{ $w->perempuan }}</td>
                            <td class="px-6 py-4 text-center align-middle">
                                <button onclick="editWilayah({{ $w->id }})" class="bg-yellow-400 text-white px-3 py-1 rounded mr-2 hover:bg-yellow-500">Edit</button>
                                <form action="{{ route('admin.wilayah.destroy', $w->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400">Belum ada data wilayah.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@include('admin.partials.footer')
@endsection
