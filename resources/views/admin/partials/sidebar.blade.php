<!-- Admin Sidebar -->
<aside 
    id="adminSidebar"
    x-data="{ 
        isOpen: $store.sidebar?.isOpen || false,
        isMinimized: false,
        isMobile: window.innerWidth < 1024,
        toggleMinimize() {
            if (this.isMobile) return; // Don't minimize on mobile
            this.isMinimized = !this.isMinimized;
            // Broadcast minimize state change
            document.dispatchEvent(new CustomEvent('sidebar-minimized', {
                detail: { minimized: this.isMinimized }
            }));
        }
    }"
    x-init="
        // Update mobile state and sidebar state on resize
        window.addEventListener('resize', () => {
            isMobile = window.innerWidth < 1024;
            if (isMobile) {
                isMinimized = false; // Reset minimize on mobile
            }
        });
        
        // Watch for store changes
        if ($store.sidebar) {
            $watch('$store.sidebar.isOpen', value => {
                isOpen = value;
            });
        }
    "
    :class="{
        'translate-x-0': isOpen,
        '-translate-x-full': !isOpen,
        'w-16': isMinimized && isOpen && !isMobile,
        'w-64': (!isMinimized && isOpen) || (isOpen && isMobile)
    }"
    class="fixed top-14 sm:top-16 left-0 h-[calc(100vh-3.5rem)] sm:h-[calc(100vh-4rem)] bg-white shadow-xl border-r border-gray-200 transition-all duration-300 ease-in-out z-40 lg:translate-x-0 w-64"
>
    <!-- Mobile Overlay -->
    <div 
        x-show="isOpen && isMobile" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.sidebar?.close()"
        class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden"
        style="display: none;"
    ></div>

    <!-- Sidebar Content -->
    <div class="relative h-full flex flex-col bg-white">
        <!-- Sidebar Header -->
        <div class="flex flex-col items-center justify-center border-b border-gray-200" :class="{'py-2': isMinimized && !isMobile, 'py-4 sm:py-6': !isMinimized || isMobile}">
            <div class="bg-gradient-to-br from-[#0088cc] to-blue-600 rounded-full flex items-center justify-center mb-2 sm:mb-3 shadow-lg p-2" :class="{'w-10 h-10': isMinimized && !isMobile, 'w-16 h-16 sm:w-20 sm:h-20': !isMinimized || iMobile}">
                <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-full h-full rounded-full object-contain bg-white p-1">
            </div>
            <div class="text-center transition-all duration-300" :class="{'hidden opacity-0': isMinimized && !isMobile, 'block opacity-100': !isMinimized || iMobile}">
                <h2 class="font-bold text-[#0088cc] text-base sm:text-lg leading-tight">DESA GANTEN</h2>
                <p class="text-xs text-gray-500 mt-1">Administrasi Desa</p>
            </div>
        </div>

        <!-- Minimize/Expand Button (Desktop only) -->
        <button 
            @click="toggleMinimize()"
            class="absolute -right-3 top-6 sm:top-8 bg-[#0088cc] text-white rounded-full w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors duration-200 hidden lg:flex"
        >
            <i class="fas fa-angle-double-left text-xs transition-transform duration-200" :class="{'rotate-180': isMinimized}"></i>
        </button>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto" :class="{'px-1 py-2': isMinimized && !isMobile, 'px-3 sm:px-4 py-4 sm:py-6': !isMinimized || isMobile}">
            <div class="space-y-1 sm:space-y-2" x-data="{ 
                activeDropdown: null,
                toggleDropdown(name) {
                    if (isMinimized && !isMobile) return; // Disable dropdowns when minimized on desktop
                    this.activeDropdown = this.activeDropdown === name ? null : name;
                }
            }">
            <!-- Dashboard -->
            <a href="/admin/dashboard" 
               class="flex items-center rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group {{ request()->is('admin/dashboard') ? 'bg-blue-50 text-[#0088cc] font-semibold' : '' }}"
               :class="{'px-2 py-2 justify-center': isMinimized && !isMobile, 'px-3 py-2.5 sm:px-4 sm:py-3': !isMinimized || isMobile}"
               :title="(isMinimized && !isMobile) ? 'Dashboard' : ''"
            >
                <i class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 {{ request()->is('admin/dashboard') ? 'text-[#0088cc]' : 'text-gray-400 group-hover:text-[#0088cc]' }}" :class="{'fas fa-tachometer-alt': true}"></i>
                <span class="ml-2 sm:ml-3 transition-all duration-300 whitespace-nowrap text-sm sm:text-base" :class="{'opacity-0 w-0 hidden': isMinimized && !isMobile, 'opacity-100 block': !isMinimized || isMobile}">Dashboard</span>
            </a>

            <!-- Profil Desa -->
            <a href="/" 
               class="flex items-center rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group"
               :class="{'px-2 py-2 justify-center': isMinimized && !isMobile, 'px-3 py-2.5 sm:px-4 sm:py-3': !isMinimized || isMobile}"
               :title="(isMinimized && !isMobile) ? 'Profil Desa' : ''"
            >
                <i class="fas fa-building w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 text-gray-400 group-hover:text-[#0088cc]"></i>
                <span class="ml-2 sm:ml-3 transition-all duration-300 whitespace-nowrap text-sm sm:text-base" :class="{'opacity-0 w-0 hidden': isMinimized && !isMobile, 'opacity-100 block': !isMinimized || isMobile}">Profil Desa</span>
            </a>

            <!-- Statistik Penduduk Dropdown -->
            <div class="relative">
                <button 
                    @click="toggleDropdown('statistik')"
                    class="flex items-center w-full rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group focus:outline-none"
                    :class="{'px-2 py-2 justify-center': isMinimized && !isMobile, 'px-3 py-2.5 sm:px-4 sm:py-3': !isMinimized || isMobile}"
                    :title="(isMinimized && !isMobile) ? 'Statistik Penduduk' : ''"
                >
                    <i class="fas fa-chart-bar w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 text-gray-400 group-hover:text-[#0088cc]"></i>
                    <span class="ml-2 sm:ml-3 flex-1 text-left transition-all duration-300 whitespace-nowrap text-sm sm:text-base" :class="{'opacity-0 w-0 hidden': isMinimized && !isMobile, 'opacity-100 block': !isMinimized || isMobile}">Statistik Penduduk</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                       :class="{'rotate-180': activeDropdown === 'statistik', 'opacity-0 hidden': isMinimized && !isMobile, 'opacity-100 block': !isMinimized || isMobile}"></i>
                </button>
                <div 
                    x-show="activeDropdown === 'statistik' && (!isMinimized || isMobile)" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="ml-6 sm:ml-8 mt-1 sm:mt-2 space-y-1"
                    style="display: none;"
                >

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-2"></div>
                    
                    <!-- Admin CRUD Menu -->
                    <div class="px-2 py-1">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Kelola Data</p>
                    </div>
                    
                    <a href="{{ route('admin.statistik.index') }}" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/statistik') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        <i class="fas fa-tachometer-alt w-3 h-3 mr-2"></i>
                        Dashboard Statistik
                    </a>
                    
                    <a href="{{ route('admin.statistik.umur') }}" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/statistik/umur*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        <i class="fas fa-birthday-cake w-3 h-3 mr-2"></i>
                        Kelola Data Umur
                    </a>
                    
                    <a href="{{ route('admin.statistik.pendidikan') }}" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/statistik/pendidikan*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        <i class="fas fa-graduation-cap w-3 h-3 mr-2"></i>
                        Kelola Data Pendidikan
                    </a>
                    
                    <a href="{{ route('admin.statistik.wilayah.index') }}" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/statistik/wilayah*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        <i class="fas fa-map-marker-alt w-3 h-3 mr-2"></i>
                        Kelola Data Wilayah
                    </a>

                    <a href="{{ route('admin.statistik.pekerjaan') }}" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/statistik/pekerjaan*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        <i class="fas fa-briefcase w-3 h-3 mr-2"></i>
                        Kelola Data Pekerjaan
                    </a>
                    
                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-2"></div>
                    
                    <!-- Public View Menu -->
                    <div class="px-2 py-1">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Lihat Publik</p>
                    </div>
                    
                    <a href="/statistik/umur" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors" target="_blank">
                        <i class="fas fa-external-link-alt w-3 h-3 mr-2"></i>
                        Lihat Data Umur
                    </a>
                    <a href="/statistik/pendidikan" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors" target="_blank">
                        <i class="fas fa-external-link-alt w-3 h-3 mr-2"></i>
                        Lihat Data Pendidikan
                    </a>
                    <a href="/statistik" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors" target="_blank">
                        <i class="fas fa-external-link-alt w-3 h-3 mr-2"></i>
                        Lihat Data Pekerjaan
                    </a>
                </div>
            </div>

            <!-- Pelayanan Surat Dropdown -->
            <div class="relative">
                <button 
                    @click="toggleDropdown('surat')"
                    class="flex items-center w-full rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group focus:outline-none {{ request()->is('admin/surat*') || request()->is('admin/verification*') ? 'bg-blue-50 text-[#0088cc] font-semibold' : '' }}"
                    :class="{'px-2 py-2 justify-center': isMinimized && !isMobile, 'px-3 py-2.5 sm:px-4 sm:py-3': !isMinimized || isMobile}"
                    :title="(isMinimized && !isMobile) ? 'Pelayanan Surat' : ''"
                >
                    <i class="fas fa-envelope w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 {{ request()->is('admin/surat*') || request()->is('admin/verification*') ? 'text-[#0088cc]' : 'text-gray-400 group-hover:text-[#0088cc]' }}"></i>
                    <span class="ml-2 sm:ml-3 flex-1 text-left transition-all duration-300 whitespace-nowrap text-sm sm:text-base" :class="{'opacity-0 w-0 hidden': isMinimized && !isMobile, 'opacity-100 block': !isMinimized || isMobile}">Pelayanan Surat</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                       :class="{'rotate-180': activeDropdown === 'surat', 'opacity-0 hidden': isMinimized && !isMobile, 'opacity-100 block': !isMinimized || isMobile}"></i>
                </button>
                <div 
                    x-show="activeDropdown === 'surat' && (!isMinimized || isMobile)" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="ml-6 sm:ml-8 mt-1 sm:mt-2 space-y-1"
                    style="display: none;"
                >
                    <a href="/admin/surat" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/surat') || request()->is('admin/surat/index') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        Pengelolaan Surat
                    </a>
                    <a href="{{ route('admin.verification.index') }}" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/verification*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        Verifikasi Surat
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/settings*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        Pengaturan Surat
                    </a>
                    {{-- <a href="{{ route('admin.test-notification') }}" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-yellow-600 hover:bg-yellow-50 hover:text-yellow-800 transition-colors {{ request()->is('admin/test-notification*') ? 'bg-yellow-50 text-yellow-800 font-medium' : '' }}">
                        <i class="fas fa-bell mr-2"></i>Test Notifikasi
                    </a> --}}
                </div>
            </div>

            <!-- Laporan Dropdown -->
            <div class="relative">
                <button 
                    @click="toggleDropdown('laporan')"
                    class="flex items-center w-full rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group focus:outline-none {{ request()->is('admin/laporan*') ? 'bg-blue-50 text-[#0088cc] font-semibold' : '' }}"
                    :class="{'px-2 py-2 justify-center': isMinimized && !isMobile, 'px-3 py-2.5 sm:px-4 sm:py-3': !isMinimized || isMobile}"
                    :title="(isMinimized && !isMobile) ? 'Laporan' : ''"
                >
                    <i class="fas fa-file-alt w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 {{ request()->is('admin/laporan*') ? 'text-[#0088cc]' : 'text-gray-400 group-hover:text-[#0088cc]' }}"></i>
                    <span class="ml-2 sm:ml-3 flex-1 text-left transition-all duration-300 whitespace-nowrap text-sm sm:text-base" :class="{'opacity-0 w-0 hidden': isMinimized && !isMobile, 'opacity-100 block': !isMinimized || isMobile}">Laporan</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                       :class="{'rotate-180': activeDropdown === 'laporan', 'opacity-0 hidden': isMinimized && !isMobile, 'opacity-100 block': !isMinimized || isMobile}"></i>
                </button>
                <div 
                    x-show="activeDropdown === 'laporan' && (!isMinimized || isMobile)" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="ml-6 sm:ml-8 mt-1 sm:mt-2 space-y-1"
                    style="display: none;"
                >
                    <a href="{{ route('admin.laporan.rekap-surat-keluar.index') }}" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/laporan/rekap-surat-keluar*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        <i class="fas fa-file-export w-3 h-3 mr-2"></i>Data Rekap Surat Keluar
                    </a>
                    {{-- <a href="/laporan/bulanan" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors">
                        <i class="fas fa-calendar-alt w-3 h-3 mr-2"></i>Laporan Bulanan
                    </a>
                    <a href="/laporan/rekap" class="block px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors">
                        <i class="fas fa-chart-line w-3 h-3 mr-2"></i>Rekap Bulanan
                    </a> --}}
                </div>
            </div>

            <!-- Kontak -->
            <a href="#kontak" 
               class="flex items-center rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group"
               :class="{'px-2 py-2 justify-center': isMinimized && !isMobile, 'px-3 py-2.5 sm:px-4 sm:py-3': !isMinimized || isMobile}"
               :title="(isMinimized && !isMobile) ? 'Kontak' : ''"
            >
                <i class="fas fa-phone w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 text-gray-400 group-hover:text-[#0088cc]"></i>
                <span class="ml-2 sm:ml-3 transition-all duration-300 whitespace-nowrap text-sm sm:text-base" :class="{'opacity-0 w-0 hidden': isMinimized && !isMobile, 'opacity-100 block': !isMinimized || isMobile}">Kontak</span>
            </a>
        </div>
    </nav>

    <!-- Footer Section -->
    <div class="border-t border-gray-200" :class="{'p-2': isMinimized && !isMobile, 'p-3 sm:p-4': !isMinimized || isMobile}">
        <div class="flex items-center justify-center" :class="{'flex-col space-y-2': (!isMinimized || isMobile), 'space-x-0': isMinimized && !isMobile}">
            <div class="text-center transition-all duration-300" :class="{'hidden opacity-0': isMinimized && !isMobile, 'block opacity-100': !isMinimized || isMobile}">
                <p class="text-xs text-gray-500">© 2025 Desa Ganten</p>
            </div>
            <div class="flex items-center justify-center" :class="{'space-x-2': (!isMinimized || isMobile), 'space-x-0': isMinimized && !isMobile}">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse" :title="(isMinimized && !isMobile) ? 'Online' : ''"></div>
                <span class="text-xs text-green-600 transition-all duration-300" :class="{'hidden opacity-0': isMinimized && !isMobile, 'block opacity-100': !isMinimized || isMobile}">Online</span>
            </div>
        </div>
    </div>
</aside>

<!-- Alpine.js Store for Sidebar State -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        isOpen: window.innerWidth >= 1024,
        isMinimized: false,
        toggle() {
            this.isOpen = !this.isOpen;
        },
        close() {
            this.isOpen = false;
        },
        open() {
            this.isOpen = true;
        },
        minimize() {
            this.isMinimized = true;
        },
        expand() {
            this.isMinimized = false;
        },
        toggleMinimize() {
            this.isMinimized = !this.isMinimized;
        }
    });
});

// Connect navbar hamburger to sidebar
document.addEventListener('DOMContentLoaded', function() {
    // Listen for navbar hamburger clicks
    const hamburgerButtons = document.querySelectorAll('[x-data*="sidebarOpen"]');
    hamburgerButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Toggle sidebar via Alpine store
            if (window.Alpine && Alpine.store('sidebar')) {
                Alpine.store('sidebar').toggle();
            }
        });
    });
});
</script>
