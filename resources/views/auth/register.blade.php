@extends('layouts.app')

{{-- Tidak ada topbar di halaman register user --}}
@section('content')
@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0088cc',
            });
        });
    </script>
@endif
<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-[#e0f7fa] via-[#f5faff] to-[#e3f2fd]">
    <div class="w-full max-w-md p-0 sm:p-8 bg-white rounded-2xl shadow-2xl flex flex-col items-center relative overflow-hidden">
        <div class="w-full flex flex-col items-center justify-center py-8 px-6">
            <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-16 h-16 rounded-full mb-4 shadow-lg border-2 border-[#0088cc] bg-white">
            <h2 class="text-3xl font-extrabold text-[#0088cc] mb-2 text-center drop-shadow">Registrasi Penduduk</h2>
            <form method="POST" action="{{ route('register') }}" class="space-y-4 w-full">
                @csrf
                <div>
                    <label class="block font-semibold mb-1 text-[#0088cc]">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border-2 border-[#b3e5fc] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] bg-[#f5faff]" required autofocus>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-[#0088cc]">Email</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border-2 border-[#b3e5fc] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] bg-[#f5faff]" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-[#0088cc]">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border-2 border-[#b3e5fc] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] bg-[#f5faff]" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1 text-[#0088cc]">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border-2 border-[#b3e5fc] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] bg-[#f5faff]" required>
                </div>
                <button type="submit" class="w-full py-2 font-bold text-white bg-gradient-to-r from-[#0088cc] to-[#006fa1] rounded-lg shadow hover:scale-105 transition">Daftar</button>
            </form>
            <div class="mt-6 text-center text-sm text-gray-600">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-[#0088cc] font-bold hover:underline">Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
