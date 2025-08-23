@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Pengguna', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
@include('admin.partials.navbar')
<div id="adminLayout" class="flex min-h-screen bg-gray-50">
    @include('admin.partials.sidebar')
    <main id="adminMain" class="flex-1 ml-0 md:ml-64 pt-24 pb-8 px-2 sm:px-4 md:px-8 transition-all duration-300">
        <div class="max-w-7xl mx-auto w-full">
            <h1 class="text-2xl md:text-3xl font-bold text-[#0088cc] mb-8 text-center md:text-left">Manajemen Pengguna</h1>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <table class="min-w-full w-full bg-white rounded border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Role</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@include('admin.partials.footer')
@endsection
