@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded shadow-md">
        <h2 class="text-2xl font-bold text-center text-[#0088cc]">Login Admin</h2>
        @if(session('error'))
            <div class="mb-4 text-red-600">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block mb-1 font-semibold">Email</label>
                <input id="email" type="email" name="email" required autofocus class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#0088cc]">
            </div>
            <div>
                <label for="password" class="block mb-1 font-semibold">Password</label>
                <input id="password" type="password" name="password" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#0088cc]">
            </div>
            <button type="submit" class="w-full py-2 font-bold text-white bg-[#0088cc] rounded hover:bg-[#006fa1]">Login</button>
        </form>
    </div>
</div>
@endsection
