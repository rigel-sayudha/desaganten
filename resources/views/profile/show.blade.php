@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-user-circle text-[#0088cc] mr-3"></i>
                    Profil Saya
                </h1>
                <p class="text-gray-600 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-[#0088cc] to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            Member sejak {{ Auth::user()->created_at->format('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Profil</h2>
                        <p class="text-gray-600 text-sm">Update informasi profil dan email Anda</p>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-gray-400"></i>
                                Nama Lengkap
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                value="{{ old('name', Auth::user()->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                Email
                            </label>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                value="{{ old('email', Auth::user()->email) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                placeholder="Masukkan email"
                                required
                            >
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIK Field -->
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                NIK (Nomor Induk Kependudukan)
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nik" 
                                name="nik" 
                                value="{{ old('nik', Auth::user()->nik) }}" 
                                placeholder="Masukkan 16 digit NIK Anda"
                                pattern="[0-9]{16}"
                                maxlength="16"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 @error('nik') border-red-500 @enderror"
                                required
                                x-data="{ 
                                    formatNik(event) {
                                        let value = event.target.value.replace(/\D/g, '');
                                        if (value.length > 16) value = value.substring(0, 16);
                                        event.target.value = value;
                                    }
                                }"
                                x-on:input="formatNik($event)"
                            >
                            @error('nik')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                NIK harus 16 digit angka. Diperlukan untuk mengajukan surat keterangan.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ url('/') }}" 
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                                Kembali
                            </a>
                            <button 
                                type="submit"
                                class="px-6 py-3 bg-[#0088cc] text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium flex items-center"
                            >
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Change Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2>
                        <p class="text-gray-600 text-sm">Update password untuk keamanan akun Anda</p>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden fields for name and email -->
                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-2 text-gray-400"></i>
                                Password Saat Ini
                            </label>
                            <input 
                                type="password" 
                                id="current_password"
                                name="current_password" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 @error('current_password') border-red-500 @enderror"
                                placeholder="Masukkan password saat ini"
                            >
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i>
                                Password Baru
                            </label>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password baru"
                            >
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i>
                                Konfirmasi Password Baru
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation"
                                name="password_confirmation" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200"
                                placeholder="Konfirmasi password baru"
                            >
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button 
                                type="button"
                                onclick="document.getElementById('current_password').value=''; document.getElementById('password').value=''; document.getElementById('password_confirmation').value='';"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium"
                            >
                                Reset
                            </button>
                            <button 
                                type="submit"
                                class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium flex items-center"
                            >
                                <i class="fas fa-shield-alt mr-2"></i>
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
