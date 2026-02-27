@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-[#e0f7fa] via-[#f5faff] to-[#e3f2fd]">
    <div class="w-full max-w-md p-0 sm:p-8 bg-white rounded-2xl shadow-2xl flex flex-col items-center relative overflow-hidden">
        <div class="w-full flex flex-col items-center justify-center py-8 px-6">
            <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-16 h-16 rounded-full mb-4 shadow-lg border-2 border-[#0088cc] bg-white">
            <h2 class="text-3xl font-extrabold text-[#0088cc] mb-2 text-center drop-shadow">Login Admin</h2>
            @if(session('error'))
                <div class="mb-4 text-red-600 text-center">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Logout',
                            text: '{{ session('success') }}',
                            confirmButtonColor: '#0088cc',
                        });
                    });
                </script>
            @endif
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4 w-full">
                @csrf
                <div>
                    <label for="email" class="block mb-1 font-semibold text-[#0088cc]">Email</label>
                    <input id="email" type="email" name="email" required autofocus class="w-full px-3 py-2 border-2 border-[#b3e5fc] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] bg-[#f5faff]">
                </div>
                <div>
                    <label for="password" class="block mb-1 font-semibold text-[#0088cc]">Password</label>
                    <input id="password" type="password" name="password" required class="w-full px-3 py-2 border-2 border-[#b3e5fc] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] bg-[#f5faff]">
                </div>
                <button type="submit" class="w-full py-2 font-bold text-white bg-gradient-to-r from-[#0088cc] to-[#006fa1] rounded-lg shadow hover:scale-105 transition">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection
