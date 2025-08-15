@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-[#0088cc] text-center">Profil Penduduk</h2>
        <div class="space-y-4">
            <div>
                <span class="font-semibold">Nama Lengkap:</span>
                <span>{{ Auth::user()->name }}</span>
            </div>
            <div>
                <span class="font-semibold">Email:</span>
                <span>{{ Auth::user()->email }}</span>
            </div>
            <!-- Tambahkan data lain sesuai kebutuhan -->
        </div>
        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-[#0088cc] text-white px-6 py-2 rounded font-bold hover:bg-[#006fa1] w-full">Logout</button>
            </form>
        </div>
    </div>
</div>
@endsection
