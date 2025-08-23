<!-- Admin Sidebar -->
<aside 
    x-data="{ 
        isOpen: window.innerWidth >= 1024,
        isMinimized: false 
    }"
    x-init="
        $watch('$store.sidebar.isOpen', value => isOpen = value);
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                isOpen = true;
            } else {
                isOpen = false;
            }
        });
    "
    :class="{
        'translate-x-0': isOpen,
        '-translate-x-full': !isOpen,
        'w-16': isMinimized && isOpen,
        'w-64': !isMinimized && isOpen
    }"
    class="fixed top-16 left-0 h-[calc(100vh-4rem)] bg-white shadow-xl border-r border-gray-200 transition-all duration-300 ease-in-out z-30 lg:translate-x-0"
    style="width: 16rem;"
>
    <!-- Sidebar Header -->
    <div class="flex flex-col items-center justify-center py-6 border-b border-gray-200">
        <div class="w-16 h-16 bg-gradient-to-br from-[#0088cc] to-blue-600 rounded-full flex items-center justify-center mb-3 shadow-lg">
            <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-14 h-14 rounded-full object-cover">
        </div>
        <div class="text-center" :class="{'hidden': isMinimized}">
            <h2 class="font-bold text-[#0088cc] text-lg leading-tight">DESA GANTEN</h2>
            <p class="text-xs text-gray-500 mt-1">Administrasi Desa</p>
        </div>
    </div>

    <!-- Minimize/Expand Button (Desktop only) -->
    <button 
        @click="isMinimized = !isMinimized"
        class="absolute -right-3 top-8 bg-[#0088cc] text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors duration-200 hidden lg:flex"
    >
        <i class="fas fa-angle-double-left text-xs transition-transform duration-200" :class="{'rotate-180': isMinimized}"></i>
    </button>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 overflow-y-auto">
        <div class="space-y-2" x-data="{ 
            activeDropdown: null,
            toggleDropdown(name) {
                this.activeDropdown = this.activeDropdown === name ? null : name;
            }
        }">
            <!-- Dashboard -->
            <a href="/admin/dashboard" 
               class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group {{ request()->is('admin/dashboard') ? 'bg-blue-50 text-[#0088cc] font-semibold' : '' }}"
               :title="isMinimized ? 'Dashboard' : ''"
            >
                <i class="fas fa-tachometer-alt w-5 h-5 flex-shrink-0 {{ request()->is('admin/dashboard') ? 'text-[#0088cc]' : 'text-gray-400 group-hover:text-[#0088cc]' }}"></i>
                <span class="ml-3 transition-opacity duration-200" :class="{'opacity-0 w-0': isMinimized, 'opacity-100': !isMinimized}">Dashboard</span>
            </a>

            <!-- Profil Desa -->
            <a href="#profil" 
               class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group"
               :title="isMinimized ? 'Profil Desa' : ''"
            >
                <i class="fas fa-building w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-[#0088cc]"></i>
                <span class="ml-3 transition-opacity duration-200" :class="{'opacity-0 w-0': isMinimized, 'opacity-100': !isMinimized}">Profil Desa</span>
            </a>

            <!-- Statistik Penduduk Dropdown -->
            <div class="relative">
                <button 
                    @click="toggleDropdown('statistik')"
                    class="flex items-center w-full px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group focus:outline-none"
                    :title="isMinimized ? 'Statistik Penduduk' : ''"
                >
                    <i class="fas fa-chart-bar w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-[#0088cc]"></i>
                    <span class="ml-3 flex-1 text-left transition-opacity duration-200" :class="{'opacity-0 w-0': isMinimized, 'opacity-100': !isMinimized}">Statistik Penduduk</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                       :class="{'rotate-180': activeDropdown === 'statistik', 'opacity-0': isMinimized, 'opacity-100': !isMinimized}"></i>
                </button>
                <div 
                    x-show="activeDropdown === 'statistik' && !isMinimized" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="ml-8 mt-2 space-y-1"
                    style="display: none;"
                >
                    <a href="/admin/wilayah" class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/wilayah*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        Wilayah
                    </a>
                    <a href="/statistik/usia" class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors">
                        Usia
                    </a>
                    <a href="/statistik/pendidikan" class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors">
                        Pendidikan
                    </a>
                    <a href="/statistik/pekerjaan" class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors">
                        Pekerjaan
                    </a>
                </div>
            </div>

            <!-- Pelayanan Surat Dropdown -->
            <div class="relative">
                <button 
                    @click="toggleDropdown('surat')"
                    class="flex items-center w-full px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group focus:outline-none"
                    :title="isMinimized ? 'Pelayanan Surat' : ''"
                >
                    <i class="fas fa-envelope-open-text w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-[#0088cc]"></i>
                    <span class="ml-3 flex-1 text-left transition-opacity duration-200" :class="{'opacity-0 w-0': isMinimized, 'opacity-100': !isMinimized}">Pelayanan Surat</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                       :class="{'rotate-180': activeDropdown === 'surat', 'opacity-0': isMinimized, 'opacity-100': !isMinimized}"></i>
                </button>
                <div 
                    x-show="activeDropdown === 'surat' && !isMinimized" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="ml-8 mt-2 space-y-1"
                    style="display: none;"
                >
                    <a href="/surat/status" class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors">
                        Status Layanan
                    </a>
                    <a href="/surat/jadwal" class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors">
                        Jadwal Pengambilan
                    </a>
                    <a href="/admin/surat" class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors {{ request()->is('admin/surat*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                        Pengelolaan Surat
                    </a>
                </div>
            </div>

            <!-- Laporan Dropdown -->
            <div class="relative">
                <button 
                    @click="toggleDropdown('laporan')"
                    class="flex items-center w-full px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group focus:outline-none"
                    :title="isMinimized ? 'Laporan' : ''"
                >
                    <i class="fas fa-file-alt w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-[#0088cc]"></i>
                    <span class="ml-3 flex-1 text-left transition-opacity duration-200" :class="{'opacity-0 w-0': isMinimized, 'opacity-100': !isMinimized}">Laporan</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                       :class="{'rotate-180': activeDropdown === 'laporan', 'opacity-0': isMinimized, 'opacity-100': !isMinimized}"></i>
                </button>
                <div 
                    x-show="activeDropdown === 'laporan' && !isMinimized" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="ml-8 mt-2 space-y-1"
                    style="display: none;"
                >
                    <a href="/laporan/bulanan" class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors">
                        Laporan Bulanan
                    </a>
                    <a href="/laporan/rekap" class="block px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-[#0088cc] transition-colors">
                        Rekap Bulanan
                    </a>
                </div>
            </div>

            <!-- Kontak -->
            <a href="#kontak" 
               class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group"
               :title="isMinimized ? 'Kontak' : ''"
            >
                <i class="fas fa-phone w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-[#0088cc]"></i>
                <span class="ml-3 transition-opacity duration-200" :class="{'opacity-0 w-0': isMinimized, 'opacity-100': !isMinimized}">Kontak</span>
            </a>
            
            <!-- Settings Menu -->
            <a href="{{ route('admin.settings') }}" 
               class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-[#0088cc] transition-all duration-200 group {{ request()->is('admin/settings*') ? 'bg-blue-50 text-[#0088cc] font-semibold' : '' }}"
               :title="isMinimized ? 'Pengaturan' : ''"
            >
                <i class="fas fa-cog w-5 h-5 flex-shrink-0 {{ request()->is('admin/settings*') ? 'text-[#0088cc]' : 'text-gray-400 group-hover:text-[#0088cc]' }}"></i>
                <span class="ml-3 transition-opacity duration-200" :class="{'opacity-0 w-0': isMinimized, 'opacity-100': !isMinimized}">Pengaturan</span>
            </a>
        </div>
    </nav>

    <!-- Footer Section -->
    <div class="border-t border-gray-200 p-4">
        <div class="flex items-center justify-center" :class="{'flex-col space-y-2': !isMinimized, 'space-x-2': isMinimized}">
            <div class="text-center" :class="{'hidden': isMinimized}">
                <p class="text-xs text-gray-500">© 2025 Desa Ganten</p>
                <p class="text-xs text-gray-400">Versi 1.0.0</p>
            </div>
            <div class="flex items-center space-x-2" :class="{'justify-center': isMinimized}">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse" :title="isMinimized ? 'Online' : ''"></div>
                <span class="text-xs text-green-600" :class="{'hidden': isMinimized}">Online</span>
            </div>
        </div>
    </div>
</aside>

<!-- Alpine.js Store for Sidebar State -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        isOpen: window.innerWidth >= 1024,
        toggle() {
            this.isOpen = !this.isOpen;
        },
        close() {
            this.isOpen = false;
        },
        open() {
            this.isOpen = true;
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
