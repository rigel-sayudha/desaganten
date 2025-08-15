@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Wilayah', 'url' => url('/admin/wilayah')],
    ['label' => 'Edit', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
@include('admin.partials.navbar')
<div class="flex">
    @include('admin.partials.sidebar')
    <main class="flex-1 ml-0 md:ml-64 min-h-screen bg-gray-50 pt-24 px-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-[#0088cc] mb-4">Edit Data Wilayah</h1>
            <form action="{{ route('admin.wilayah.update', $wilayah->id) }}" method="POST" class="bg-white rounded-lg shadow-lg p-6 max-w-lg">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Nama Wilayah</label>
                    <input type="text" name="nama" class="w-full border rounded px-3 py-2" value="{{ $wilayah->nama }}" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Jumlah</label>
                    <input type="number" name="jumlah" class="w-full border rounded px-3 py-2" value="{{ $wilayah->jumlah }}" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Laki-laki</label>
                    <input type="number" name="laki_laki" class="w-full border rounded px-3 py-2" value="{{ $wilayah->laki_laki }}" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Perempuan</label>
                    <input type="number" name="perempuan" class="w-full border rounded px-3 py-2" value="{{ $wilayah->perempuan }}" required>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="bg-[#0088cc] text-white px-4 py-2 rounded hover:bg-[#006fa1] font-semibold">Update</button>
                    <a href="{{ route('admin.wilayah.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded font-semibold">Batal</a>
                </div>
            </form>
        </div>
    </main>
</div>
@include('admin.partials.footer')
@endsection
