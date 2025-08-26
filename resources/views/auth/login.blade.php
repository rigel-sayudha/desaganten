@extends('layouts.app')

@section('topbar')
@endsection

@section('content')
<!-- Modern Login Page -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-72 h-72 bg-gradient-to-br from-[#0088cc] to-blue-400 rounded-full opacity-10 blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-96 h-96 bg-gradient-to-tr from-blue-300 to-cyan-200 rounded-full opacity-15 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-gradient-to-r from-[#0088cc] to-blue-500 rounded-full opacity-5 blur-xl"></div>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10">
        
        <!-- Header Section -->
        <div class="text-center">
            <!-- Logo and Branding -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="w-20 h-20 bg-white rounded-full shadow-lg flex items-center justify-center relative overflow-hidden">
                        <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-16 h-16 rounded-full object-cover">
                    </div>
                    <div class="absolute -inset-1 bg-gradient-to-r from-[#0088cc] to-blue-400 rounded-full opacity-20 blur-sm"></div>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
            <p class="text-gray-600 text-sm">
                Masuk ke Portal <span class="font-semibold text-[#0088cc]">Desa Ganten</span>
            </p>
        </div>

        <!-- Login Form Container -->
        <div class="bg-white backdrop-blur-lg bg-opacity-80 rounded-2xl shadow-xl border border-white border-opacity-50 p-8 space-y-6">
            
            <!-- Error Messages -->
            @if(session('error') || $errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <div>
                            @if(session('error'))
                                <span class="text-red-700 text-sm">{{ session('error') }}</span>
                            @endif
                            @if($errors->has('email'))
                                <span class="text-red-700 text-sm">{{ $errors->first('email') }}</span>
                            @endif
                            @if($errors->has('password'))
                                <span class="text-red-700 text-sm block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ 
                showPassword: false,
                email: '{{ old('email') }}',
                password: '',
                isLoading: false,
                submitForm() {
                    if (!this.email || !this.password) {
                        return false;
                    }
                    this.isLoading = true;
                    // Reset loading state after a timeout to prevent permanent loading
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 8000);
                    // Let the form submit naturally
                    return true;
                }
            }" 
            @submit="if (!isLoading) { return submitForm(); } else { $event.preventDefault(); }"
            x-init="
                // Reset loading state if there are errors (page reload)
                @if($errors->any() || session('error'))
                    isLoading = false;
                @endif
            ">
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-envelope text-[#0088cc]"></i>
                        <span>Alamat Email</span>
                    </label>
                    <div class="relative">
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            x-model="email"
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400 bg-white @error('email') border-red-500 @else border-gray-300 @enderror"
                            placeholder="Masukkan email Anda"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-at text-gray-400" x-show="!email"></i>
                            <i class="fas fa-check text-green-500" x-show="email && email.includes('@')"></i>
                        </div>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-lock text-[#0088cc]"></i>
                        <span>Kata Sandi</span>
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            :type="showPassword ? 'text' : 'password'" 
                            name="password" 
                            x-model="password"
                            required
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400 bg-white pr-12 @error('password') border-red-500 @else border-gray-300 @enderror"
                            placeholder="Masukkan kata sandi"
                        >
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i class="fas fa-eye" x-show="!showPassword"></i>
                            <i class="fas fa-eye-slash" x-show="showPassword"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 text-sm text-gray-600">
                        <input type="checkbox" class="w-4 h-4 text-[#0088cc] border-gray-300 rounded focus:ring-[#0088cc]">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-[#0088cc] hover:text-blue-600 transition-colors">
                        Lupa kata sandi?
                    </a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit"
                    :disabled="isLoading || !email || !password"
                    :class="{
                        'opacity-50 cursor-not-allowed': isLoading || !email || !password,
                        'hover:bg-blue-600 hover:shadow-lg transform hover:-translate-y-0.5': !isLoading && email && password
                    }"
                    class="w-full bg-gradient-to-r from-[#0088cc] to-blue-500 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 shadow-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:ring-offset-2"
                >
                    <span x-show="!isLoading" class="flex items-center justify-center space-x-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </span>
                    <span x-show="isLoading" class="flex items-center justify-center space-x-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span>Memproses...</span>
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">atau</span>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-medium text-[#0088cc] hover:text-blue-600 transition-colors">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} Desa Ganten. Portal resmi untuk pelayanan digital.
            </p>
            <div class="flex justify-center space-x-4 mt-2">
                <a href="{{ url('/') }}" class="text-xs text-[#0088cc] hover:text-blue-600 transition-colors">
                    Kembali ke Beranda
                </a>
                <span class="text-xs text-gray-300">•</span>
                <a href="#" class="text-xs text-[#0088cc] hover:text-blue-600 transition-colors">
                    Bantuan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js Script -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
