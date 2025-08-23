<!-- Admin Navbar -->
<nav id="adminNavbar" x-data="{ 
    sidebarOpen: false,
    profileDropdown: false 
}" class="fixed top-0 left-0 w-full bg-[#0088cc] text-white shadow-lg z-50">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left section with hamburger and logo -->
            <div class="flex items-center space-x-3">
                <!-- Hamburger for mobile/tablet -->
                <button 
                    @click="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-white transition-colors duration-200"
                    aria-label="Toggle sidebar"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <!-- Logo and title -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full p-1">
                        <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="font-bold text-lg lg:text-xl">Admin Desa Ganten</h1>
                        <p class="text-xs text-blue-200 hidden md:block">Sistem Administrasi Desa</p>
                    </div>
                </div>
            </div>

            <!-- Right section with user menu -->
            <div class="flex items-center space-x-4">
                <!-- Welcome message (hidden on small screens) -->
                <span class="hidden md:inline-block text-sm lg:text-base">
                    Selamat datang, <span class="font-semibold">{{ Auth::user()->name ?? 'Admin' }}</span>
                </span>

                <!-- Profile dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button 
                        @click="open = !open"
                        @click.away="open = false"
                        class="flex items-center space-x-2 p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-white transition-colors duration-200"
                    >
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-[#0088cc] text-sm"></i>
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
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                        style="display: none;"
                    >
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@desaganten.id' }}</p>
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
    </script>
</nav>

