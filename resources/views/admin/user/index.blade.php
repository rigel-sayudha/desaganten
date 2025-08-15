@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Data User', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
@include('admin.partials.navbar')
<div id="adminLayout" class="flex min-h-screen bg-gray-50">
    @include('admin.partials.sidebar')
    <main id="adminMain" class="flex-1 ml-0 md:ml-64 pt-24 pb-8 px-4 md:px-8 transition-all duration-300">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-2xl font-bold text-[#0088cc] mb-6">Kelola Data User</h1>
            <div class="overflow-x-auto bg-white rounded-lg shadow-lg min-h-[400px] flex flex-col justify-between">
                <table class="min-w-full divide-y divide-blue-200">
                    <thead class="bg-[#0088cc]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-blue-100 min-h-[320px]">
                        @forelse($users as $user)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-700 align-middle">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-700 align-middle">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-center align-middle">
                                <a href="{{ route('admin.user.edit', $user->id) }}" class="text-[#0088cc] font-bold mr-2">Edit</a>
                                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-600 font-bold btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-16 text-gray-400 align-middle">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-database fa-3x mb-4 text-blue-100"></i>
                                    <span class="text-lg">Belum ada data user.</span>
                                </div>
                            </td>
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
