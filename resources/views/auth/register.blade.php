@extends('layouts.app')

@section('topbar')
@endsection

@section('content')
<!-- Modern Register Page -->
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-72 h-72 bg-gradient-to-br from-green-400 to-emerald-300 rounded-full opacity-10 blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-96 h-96 bg-gradient-to-tr from-[#0088cc] to-blue-300 rounded-full opacity-15 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-gradient-to-r from-green-300 to-[#0088cc] rounded-full opacity-5 blur-xl"></div>
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
                    <div class="absolute -inset-1 bg-gradient-to-r from-green-400 to-[#0088cc] rounded-full opacity-20 blur-sm"></div>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Bergabung Bersama</h2>
            <p class="text-gray-600 text-sm">
                Daftar untuk mengakses layanan <span class="font-semibold text-[#0088cc]">Desa Ganten</span>
            </p>
        </div>

        <!-- Register Form Container -->
        <div class="bg-white backdrop-blur-lg bg-opacity-80 rounded-2xl shadow-xl border border-white border-opacity-50 p-8 space-y-6">
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div class="flex-1">
                            <h4 class="text-red-800 font-medium text-sm">Terdapat kesalahan:</h4>
                            <ul class="text-red-700 text-sm mt-1 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-red-700 text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ 
                showPassword: false,
                showPasswordConfirmation: false,
                name: '{{ old('name') }}',
                email: '{{ old('email') }}',
                password: '',
                passwordConfirmation: '',
                isLoading: false,
                submitForm() {
                    if (!this.isLoading && this.name && this.email && this.password && this.passwordConfirmation && this.password === this.passwordConfirmation) {
                        this.isLoading = true;
                        this.$el.submit();
                    }
                }
            }" @submit.prevent="submitForm()">
                @csrf
                
                <!-- Name Field -->
                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-user text-green-500"></i>
                        <span>Nama Lengkap</span>
                    </label>
                    <div class="relative">
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            x-model="name"
                            value="{{ old('name') }}"
                            required 
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 placeholder-gray-400 bg-white"
                            placeholder="Masukkan nama lengkap Anda"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-check text-green-500" x-show="name && name.length >= 3"></i>
                        </div>
                    </div>
                </div>

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
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition duration-200 placeholder-gray-400 bg-white"
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
                        <i class="fas fa-lock text-purple-500"></i>
                        <span>Kata Sandi</span>
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            :type="showPassword ? 'text' : 'password'" 
                            name="password" 
                            x-model="password"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 placeholder-gray-400 bg-white pr-12"
                            placeholder="Minimal 8 karakter"
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
                    <!-- Password Strength Indicator -->
                    <div x-show="password" class="space-y-1">
                        <div class="flex space-x-1">
                            <div class="h-1 w-full rounded" :class="password.length >= 8 ? 'bg-green-400' : 'bg-gray-200'"></div>
                            <div class="h-1 w-full rounded" :class="password.length >= 8 && /[A-Z]/.test(password) ? 'bg-green-400' : 'bg-gray-200'"></div>
                            <div class="h-1 w-full rounded" :class="password.length >= 8 && /[0-9]/.test(password) ? 'bg-green-400' : 'bg-gray-200'"></div>
                        </div>
                        <p class="text-xs text-gray-500">
                            <span :class="password.length >= 8 ? 'text-green-600' : 'text-gray-500'">• Minimal 8 karakter</span>
                        </p>
                    </div>
                </div>

                <!-- Password Confirmation Field -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-lock text-orange-500"></i>
                        <span>Konfirmasi Kata Sandi</span>
                    </label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            :type="showPasswordConfirmation ? 'text' : 'password'" 
                            name="password_confirmation" 
                            x-model="passwordConfirmation"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 placeholder-gray-400 bg-white pr-12"
                            placeholder="Ulangi kata sandi"
                        >
                        <button 
                            type="button"
                            @click="showPasswordConfirmation = !showPasswordConfirmation"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i class="fas fa-eye" x-show="!showPasswordConfirmation"></i>
                            <i class="fas fa-eye-slash" x-show="showPasswordConfirmation"></i>
                        </button>
                    </div>
                    <p x-show="passwordConfirmation && password !== passwordConfirmation" class="text-xs text-red-500">
                        <i class="fas fa-times mr-1"></i>Kata sandi tidak cocok
                    </p>
                    <p x-show="passwordConfirmation && password === passwordConfirmation && password" class="text-xs text-green-600">
                        <i class="fas fa-check mr-1"></i>Kata sandi cocok
                    </p>
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-start space-x-3">
                    <input type="checkbox" required class="w-4 h-4 text-[#0088cc] border-gray-300 rounded focus:ring-[#0088cc] mt-1">
                    <label class="text-sm text-gray-600 leading-relaxed">
                        Saya setuju dengan 
                        <a href="#" class="text-[#0088cc] hover:text-blue-600 transition-colors font-medium">Syarat & Ketentuan</a> 
                        dan 
                        <a href="#" class="text-[#0088cc] hover:text-blue-600 transition-colors font-medium">Kebijakan Privasi</a>
                        yang berlaku.
                    </label>
                </div>

                <!-- Register Button -->
                <button 
                    type="submit"
                    :disabled="isLoading || !name || !email || !password || !passwordConfirmation || password !== passwordConfirmation"
                    :class="{
                        'opacity-50 cursor-not-allowed': isLoading || !name || !email || !password || !passwordConfirmation || password !== passwordConfirmation,
                        'hover:bg-green-600 hover:shadow-lg transform hover:-translate-y-0.5': !isLoading && name && email && password && passwordConfirmation && password === passwordConfirmation
                    }"
                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    <span x-show="!isLoading" class="flex items-center justify-center space-x-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar Sekarang</span>
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

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-medium text-[#0088cc] hover:text-blue-600 transition-colors">
                        Masuk di sini
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

<!-- Register Form Handler -->
<script src="{{ asset('js/register.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reset loading state when page loads (in case of validation errors)
    const form = document.querySelector('form[x-data]');
    if (form && form._x_dataStack && form._x_dataStack[0]) {
        form._x_dataStack[0].isLoading = false;
    }
    
    // Reset loading state if there are validation errors
    @if($errors->any() || session('error'))
    setTimeout(() => {
        const form = document.querySelector('form[x-data]');
        if (form && form._x_dataStack && form._x_dataStack[0]) {
            form._x_dataStack[0].isLoading = false;
        }
    }, 100);
    @endif
});
</script>
@endsection
