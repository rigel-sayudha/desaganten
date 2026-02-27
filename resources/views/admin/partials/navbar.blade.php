<!-- Admin Navbar -->
<nav id="adminNavbar" x-data="{ 
    sidebarOpen: $store.sidebar?.isOpen || false,
    profileDropdown: false,
    editProfile: false,
    profileForm: {
        name: '{{ Auth::user()->name ?? 'Admin' }}',
        email: '{{ Auth::user()->email ?? 'admin@desaganten.id' }}',
        current_password: '',
        password: '',
        password_confirmation: ''
    },
    isLoading: false,
    errors: {},
    showPasswordFields: false,
    isMobile: window.innerWidth < 1024
}" 
x-init="
    // Update mobile state on resize
    window.addEventListener('resize', () => {
        isMobile = window.innerWidth < 1024;
    });
    
    // Watch for sidebar state changes
    if ($store.sidebar) {
        $watch('$store.sidebar.isOpen', value => {
            sidebarOpen = value;
        });
    }
"
class="fixed top-0 left-0 w-full bg-[#0088cc] text-white shadow-lg z-50">
    <div class="px-3 sm:px-4 lg:px-6">
        <div class="flex items-center justify-between h-14 sm:h-16">
            <!-- Left section with hamburger and logo -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <!-- Hamburger for mobile/tablet -->
                <button 
                    id="hamburgerButton"
                    @click="$store.sidebar?.toggle()" 
                    class="lg:hidden p-1.5 sm:p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-white transition-colors duration-200"
                    aria-label="Toggle sidebar"
                >
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <!-- Logo and title -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-full flex items-center justify-center p-1.5">
                        <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-full h-full rounded-full object-contain">
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="font-bold text-base sm:text-lg lg:text-xl">Admin Desa Ganten</h1>
                        <p class="text-xs text-blue-200 hidden md:block">Sistem Administrasi Desa</p>
                    </div>
                </div>
            </div>

            <!-- Right section with user menu -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Welcome message (hidden on small screens) -->
                <span class="hidden lg:inline-block text-sm xl:text-base">
                    Selamat datang, <span class="font-semibold">{{ Auth::user()->name ?? 'Admin' }}</span>
                </span>

                <!-- Profile dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button 
                        @click="open = !open"
                        @click.away="open = false"
                        class="flex items-center space-x-1 sm:space-x-2 p-1.5 sm:p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-white transition-colors duration-200"
                    >
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-white rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-[#0088cc] text-xs sm:text-sm"></i>
                        </div>
                        <i class="fas fa-chevron-down text-xs hidden sm:block" :class="{'rotate-180': open}"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <div 
                        x-show="open" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-44 sm:w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                        style="display: none;"
                    >
                        <div class="px-3 sm:px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@desaganten.id' }}</p>
                        </div>
                        
                        <button 
                            @click="editProfile = true; open = false"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                        >
                            <i class="fas fa-user-edit w-4 h-4 mr-3"></i>
                            Edit Profil
                        </button>
                        
                        <form method="POST" action="{{ route('admin.logout') }}" id="navbarLogoutForm">
                            @csrf
                            <button 
                                type="button" 
                                @click="logout()"
                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                            >
                                <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"
        style="display: none;"
    ></div>

    <!-- Edit Profile Modal -->
    <div 
        x-show="editProfile" 
        @click.self="editProfile = false"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            x-show="editProfile"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto"
            @click.stop
        >
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-black flex items-center">
                    <i class="fas fa-user-edit text-[#0088cc] mr-3"></i>
                    Edit Profil Admin
                </h3>
                <button 
                    @click="editProfile = false"
                    class="text-gray-400 hover:text-gray-600 transition-colors"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form onsubmit="event.preventDefault(); updateProfile();" class="p-6 space-y-6">
                <!-- Profile Picture Section -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-[#0088cc] to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                    <button type="button" class="text-sm text-[#0088cc] hover:text-blue-600 font-medium">
                        <i class="fas fa-camera mr-1"></i>
                        Ubah Foto Profil
                    </button>
                </div>

                <!-- Name Field -->
                <div>
                    <label class="block text-sm font-medium text-black mb-2">
                        <i class="fas fa-user mr-2 text-gray-400"></i>
                        Nama Lengkap
                    </label>
                    <input 
                        x-model="profileForm.name"
                        type="text" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-500"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
                    <div x-show="errors.name" class="text-red-500 text-sm mt-1" x-text="errors.name"></div>
                </div>

                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-medium text-black mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>
                        Email
                    </label>
                    <input 
                        x-model="profileForm.email"
                        type="email" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-500"
                        placeholder="Masukkan email"
                        required
                    >
                    <div x-show="errors.email" class="text-red-500 text-sm mt-1" x-text="errors.email"></div>
                </div>

                <!-- Password Change Section -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-black">Ubah Password</h4>
                        <button 
                            type="button"
                            @click="showPasswordFields = !showPasswordFields"
                            class="text-sm text-[#0088cc] hover:text-blue-600 font-medium"
                        >
                            <span x-text="showPasswordFields ? 'Batal' : 'Ubah Password'"></span>
                        </button>
                    </div>

                    <div x-show="showPasswordFields" class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">
                                <i class="fas fa-key mr-2 text-gray-400"></i>
                                Password Saat Ini
                            </label>
                            <input 
                                x-model="profileForm.current_password"
                                type="password" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-500"
                                placeholder="Masukkan password saat ini"
                            >
                            <div x-show="errors.current_password" class="text-red-500 text-sm mt-1" x-text="errors.current_password"></div>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i>
                                Password Baru
                            </label>
                            <input 
                                x-model="profileForm.password"
                                type="password" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-500"
                                placeholder="Masukkan password baru"
                            >
                            <div x-show="errors.password" class="text-red-500 text-sm mt-1" x-text="errors.password"></div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i>
                                Konfirmasi Password Baru
                            </label>
                            <input 
                                x-model="profileForm.password_confirmation"
                                type="password" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0088cc] focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-500"
                                placeholder="Konfirmasi password baru"
                            >
                            <div x-show="errors.password_confirmation" class="text-red-500 text-sm mt-1" x-text="errors.password_confirmation"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button 
                        type="button"
                        @click="editProfile = false"
                        class="px-6 py-3 border border-gray-300 text-black rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="isLoading"
                        class="px-6 py-3 bg-[#0088cc] text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium flex items-center"
                    >
                        <i class="fas fa-spinner fa-spin mr-2" x-show="isLoading"></i>
                        <i class="fas fa-save mr-2" x-show="!isLoading"></i>
                        <span x-text="isLoading ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pass sidebar state to global scope -->
    <script>
        window.adminSidebar = {
            toggle: function() {
                // This will be handled by Alpine.js
            }
        };

        function logout() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Anda yakin ingin keluar dari admin?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0088cc',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('navbarLogoutForm').submit();
                    }
                });
            } else {
                if (confirm('Anda yakin ingin logout?')) {
                    document.getElementById('navbarLogoutForm').submit();
                }
            }
        }

        // Simple and robust function to update profile
        function updateProfile() {
            console.log('updateProfile function called');
            
            // Show basic loading indicator
            const submitButton = document.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                const buttonText = submitButton.querySelector('span');
                if (buttonText) {
                    buttonText.textContent = 'Menyimpan...';
                }
            }
            
            // Get form values directly from inputs
            const nameInput = document.querySelector('input[x-model="profileForm.name"]');
            const emailInput = document.querySelector('input[x-model="profileForm.email"]');
            const currentPasswordInput = document.querySelector('input[x-model="profileForm.current_password"]');
            const passwordInput = document.querySelector('input[x-model="profileForm.password"]');
            const passwordConfirmationInput = document.querySelector('input[x-model="profileForm.password_confirmation"]');
            
            if (!nameInput || !emailInput) {
                alert('Form elements not found');
                if (submitButton) {
                    submitButton.disabled = false;
                    const buttonText = submitButton.querySelector('span');
                    if (buttonText) buttonText.textContent = 'Simpan Perubahan';
                }
                return;
            }
            
            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const currentPassword = currentPasswordInput ? currentPasswordInput.value : '';
            const password = passwordInput ? passwordInput.value : '';
            const passwordConfirmation = passwordConfirmationInput ? passwordConfirmationInput.value : '';
            
            console.log('Form values:', { name, email, hasCurrentPassword: !!currentPassword, hasPassword: !!password });

            // Basic validation
            if (!name || !email) {
                alert('Nama dan email harus diisi');
                if (submitButton) {
                    submitButton.disabled = false;
                    const buttonText = submitButton.querySelector('span');
                    if (buttonText) buttonText.textContent = 'Simpan Perubahan';
                }
                return;
            }

            // Check if password fields are visible (not just exist in DOM)
            const showPasswordFields = currentPasswordInput && currentPasswordInput.offsetParent !== null;
            
            // Validate password fields if visible and filled
            if (showPasswordFields && (currentPassword || password || passwordConfirmation)) {
                if (!currentPassword) {
                    alert('Password saat ini harus diisi');
                    if (submitButton) {
                        submitButton.disabled = false;
                        const buttonText = submitButton.querySelector('span');
                        if (buttonText) buttonText.textContent = 'Simpan Perubahan';
                    }
                    return;
                }
                if (!password) {
                    alert('Password baru harus diisi');
                    if (submitButton) {
                        submitButton.disabled = false;
                        const buttonText = submitButton.querySelector('span');
                        if (buttonText) buttonText.textContent = 'Simpan Perubahan';
                    }
                    return;
                }
                if (password !== passwordConfirmation) {
                    alert('Konfirmasi password tidak cocok');
                    if (submitButton) {
                        submitButton.disabled = false;
                        const buttonText = submitButton.querySelector('span');
                        if (buttonText) buttonText.textContent = 'Simpan Perubahan';
                    }
                    return;
                }
                if (password.length < 8) {
                    alert('Password minimal 8 karakter');
                    if (submitButton) {
                        submitButton.disabled = false;
                        const buttonText = submitButton.querySelector('span');
                        if (buttonText) buttonText.textContent = 'Simpan Perubahan';
                    }
                    return;
                }
            }

            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found in meta tag');
                alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
                if (submitButton) {
                    submitButton.disabled = false;
                    const buttonText = submitButton.querySelector('span');
                    if (buttonText) buttonText.textContent = 'Simpan Perubahan';
                }
                return;
            }

            // Prepare FormData
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('_token', csrfToken.getAttribute('content'));
            formData.append('_method', 'PUT');
            
            // Only include password fields if they are visible and filled
            if (showPasswordFields && currentPassword && password) {
                formData.append('current_password', currentPassword);
                formData.append('password', password);
                formData.append('password_confirmation', passwordConfirmation);
            }

            console.log('Sending request to profile update endpoint...');

            // Make the request
            fetch('{{ route("admin.profile.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response received, status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    // Success response
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message || 'Profil berhasil diperbarui',
                            icon: 'success',
                            confirmButtonColor: '#0088cc'
                        }).then(() => {
                            // Reload the page to reflect changes
                            window.location.reload();
                        });
                    } else {
                        alert(data.message || 'Profil berhasil diperbarui');
                        window.location.reload();
                    }
                } else {
                    // Error response
                    let errorMessage = data.message || 'Terjadi kesalahan saat memperbarui profil';
                    
                    if (data.errors) {
                        const errorArray = Object.values(data.errors).flat();
                        errorMessage = errorArray.join('\n');
                    }
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonColor: '#0088cc'
                        });
                    } else {
                        alert(errorMessage);
                    }
                }
            })
            .catch(error => {
                console.error('Request failed:', error);
                
                let errorMessage = 'Terjadi kesalahan jaringan';
                if (error.message.includes('419')) {
                    errorMessage = 'Session expired. Silakan refresh halaman dan coba lagi.';
                } else if (error.message.includes('500')) {
                    errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.';
                }
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonColor: '#0088cc'
                    });
                } else {
                    alert(errorMessage);
                }
            })
            .finally(() => {
                // Re-enable the submit button
                if (submitButton) {
                    submitButton.disabled = false;
                    const buttonText = submitButton.querySelector('span');
                    if (buttonText) {
                        buttonText.textContent = 'Simpan Perubahan';
                    }
                }
            });
        }
    </script>
</nav>

