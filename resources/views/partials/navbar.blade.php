<!-- User Navbar -->
<nav 
    x-data="{ 
        mobileMenuOpen: false,
        scrolled: false,
        userDropdownOpen: false,
        notifDropdownOpen: false,
        activeDropdown: null
    }"
    x-init="
        window.addEventListener('scroll', () => {
            scrolled = window.scrollY > 50;
        });
        document.addEventListener('click', (e) => {
            if (!$refs.userDropdown?.contains(e.target)) userDropdownOpen = false;
            if (!$refs.notifDropdown?.contains(e.target)) notifDropdownOpen = false;
        });
    "
    :class="{
        'bg-white/95 backdrop-blur-md shadow-lg': scrolled,
        'bg-transparent': !scrolled
    }"
    class="fixed w-full z-50 transition-all duration-300"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            
            <!-- Logo Section -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <a href="/" class="flex items-center space-x-3 group">
                    <img src="/img/logo.png" alt="Logo Desa Ganten" class="h-10 lg:h-12 w-auto object-contain">
                    <div class="flex flex-col">
                        <span class="font-bold text-lg lg:text-xl text-[#0088cc] leading-tight">DESA GANTEN</span>
                        <span class="text-xs lg:text-sm text-gray-600 hidden sm:block">KERJO - KARANGANYAR</span>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <!-- Main Navigation Links -->
                <a href="{{ url('/') }}" 
                   class="px-4 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors font-medium">
                    Beranda
                </a>
                
                <a href="{{ url('/') }}#profil" 
                   class="px-4 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors font-medium">
                    Profil Desa
                </a>

                <a href="{{ route('statistik.main') }}" 
                    class="px-4 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors font-medium">
                     Statistik Penduduk
                </a>

                <!-- Pelayanan Surat Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="flex items-center px-4 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors font-medium">
                        Pelayanan Surat
                        <i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-2 z-50"
                        style="display: none;"
                    >
                        @auth
                        <a href="{{ route('user.surat.index') }}" class="block px-4 py-2 text-black hover:bg-blue-50 hover:text-black transition-colors">
                            <i class="fas fa-list-check mr-2"></i>Status Surat Saya
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        @endauth
                        <a href="/surat/form" class="block px-4 py-2 text-black hover:bg-blue-50 hover:text-black transition-colors">Pelayanan Surat</a>
                    </div>
                </div>

                <!-- Laporan Dropdown (Admin Only) -->
                @if(Auth::check() && Auth::user()->role === 'admin')
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="flex items-center px-4 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors font-medium">
                        Laporan
                        <i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-2 z-50"
                        style="display: none;"
                    >
                        {{-- <a href="/laporan/bulanan" class="block px-4 py-2 text-black hover:bg-blue-50 hover:text-black transition-colors">Laporan Bulanan</a>
                        <a href="/laporan/rekap" class="block px-4 py-2 text-black hover:bg-blue-50 hover:text-black transition-colors">Rekap Bulanan</a> --}}
                        <a href="{{ route('laporan.rekap-surat-keluar') }}" class="block px-4 py-2 text-black hover:bg-blue-50 hover:text-black transition-colors">Data Rekap Surat Keluar</a>
                    </div>
                </div>
                @endif

                <a href="{{ url('/') }}#kontak" 
                   class="px-4 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors font-medium">
                    Kontak
                </a>
            </div>

            <!-- Right Section - Notifications & User Menu -->
            <div class="flex items-center space-x-3">
                @auth
                <!-- Notification Bell -->
                <div class="relative" x-data="{ 
                    open: false, 
                    notifCount: 0, 
                    notifications: [],
                    loading: false,
                    async loadNotifications() {
                        this.loading = true;
                        try {
                            const response = await fetch('/notifications');
                            const data = await response.json();
                            this.notifications = data.notifications;
                        } catch (error) {
                            console.error('Error loading notifications:', error);
                        }
                        this.loading = false;
                    },
                    async loadNotifCount() {
                        try {
                            const response = await fetch('/notifications/count');
                            const data = await response.json();
                            this.notifCount = data.count;
                        } catch (error) {
                            console.error('Error loading notification count:', error);
                        }
                    },
                    async markAsRead(notifId) {
                        try {
                            await fetch(`/notifications/${notifId}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });
                            await this.loadNotifCount();
                            await this.loadNotifications();
                        } catch (error) {
                            console.error('Error marking notification as read:', error);
                        }
                    },
                    async markAllAsRead() {
                        try {
                            await fetch('/notifications/read-all', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });
                            await this.loadNotifCount();
                            await this.loadNotifications();
                        } catch (error) {
                            console.error('Error marking all notifications as read:', error);
                        }
                    }
                }" 
                x-init="
                    loadNotifCount();
                    // Refresh count every 30 seconds
                    setInterval(() => loadNotifCount(), 30000);
                "
                x-ref="notifDropdown">
                    <button 
                        @click="
                            open = !open; 
                            if (open && notifications.length === 0) loadNotifications()
                        "
                        class="p-2 rounded-lg text-black hover:bg-blue-50 transition-colors relative"
                    >
                        <i class="fas fa-bell text-xl"></i>
                        <span 
                            x-show="notifCount > 0"
                            x-text="notifCount > 99 ? '99+' : notifCount"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full min-w-5 h-5 flex items-center justify-center font-bold px-1"
                        ></span>
                    </button>
                    
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-100 z-50 max-h-96 overflow-hidden"
                        style="display: none;"
                    >
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                            <button 
                                @click="markAllAsRead()"
                                x-show="notifCount > 0"
                                class="text-sm text-blue-600 hover:text-blue-800 transition-colors"
                            >
                                Tandai Semua Dibaca
                            </button>
                        </div>

                        <!-- Loading State -->
                        <div x-show="loading" class="p-4 text-center">
                            <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            <p class="text-gray-500 text-sm mt-2">Memuat notifikasi...</p>
                        </div>

                        <!-- Notifications List -->
                        <div class="max-h-64 overflow-y-auto" x-show="!loading">
                            <template x-for="notification in notifications" :key="notification.id">
                                <div 
                                    @click="
                                        if (notification.is_unread) markAsRead(notification.id)
                                    "
                                    :class="{
                                        'bg-blue-50': notification.is_unread,
                                        'bg-white': !notification.is_unread
                                    }"
                                    class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer"
                                >
                                    <div class="flex items-start space-x-3">
                                        <div :class="notification.color" class="mt-1">
                                            <i :class="notification.icon"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 text-sm" x-text="notification.title"></p>
                                            <p class="text-gray-600 text-sm" x-text="notification.message"></p>
                                            <p class="text-gray-400 text-xs mt-1" x-text="notification.created_at"></p>
                                        </div>
                                        <div x-show="notification.is_unread" class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    </div>
                                </div>
                            </template>

                            <!-- Empty State -->
                            <div x-show="!loading && notifications.length === 0" class="p-6 text-center">
                                <i class="fas fa-bell-slash text-gray-300 text-3xl mb-3"></i>
                                <p class="text-gray-500">Belum ada notifikasi</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                @endauth

                <!-- User Menu or Auth Buttons -->
                @if(Auth::check())
                    <div class="relative" x-data="{ open: false }" x-ref="userDropdown">
                        <button 
                            @click="open = !open"
                            class="flex items-center space-x-2 bg-white text-black px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 border border-blue-100"
                        >
                            <div class="w-8 h-8 bg-gradient-to-br from-[#0088cc] to-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="font-medium text-sm hidden sm:block max-w-32 truncate">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                        </button>
                        
                        <div 
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-2 z-50"
                            style="display: none;"
                        >
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="font-medium text-black text-sm">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-black">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-black hover:bg-blue-50 hover:text-black transition-colors">
                                <i class="fas fa-user w-4 h-4 mr-3"></i>
                                Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button 
                                    type="button" 
                                    @click="logout()"
                                    class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 transition-colors"
                                >
                                    <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="hidden lg:flex items-center space-x-3">
                        <a href="{{ route('login') }}" 
                           class="px-4 py-2 text-black hover:bg-blue-50 rounded-lg transition-colors font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-2 bg-[#0088cc] text-white rounded-lg hover:bg-blue-600 transition-colors font-medium shadow-md">
                            Register
                        </a>
                    </div>
                @endif

                <!-- Mobile Menu Button -->
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden p-2 rounded-lg text-black hover:bg-blue-50 transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div 
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="lg:hidden bg-white border-t border-gray-100 shadow-lg"
            style="display: none;"
        >
            <div class="px-4 py-3 space-y-1">
                <!-- Main Navigation Links -->
                <a href="{{ url('/') }}" 
                   class="block px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors {{ request()->is('/') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                
                <a href="{{ url('/') }}#profil" 
                   class="block px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors">
                    <i class="fas fa-building mr-2"></i>Profil Desa
                </a>

                <a href="{{ route('statistik.main') }}" 
                   class="block px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors {{ request()->routeIs('statistik.*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                    <i class="fas fa-chart-bar mr-2"></i>Statistik Penduduk
                </a>

                <!-- Pelayanan Surat Dropdown -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors">
                        <span class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            Pelayanan Surat
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-1"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="pl-6 mt-1 space-y-1 bg-gray-50 rounded-lg py-2">
                        @auth
                        <a href="{{ route('user.surat.index') }}" 
                           class="block px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors {{ request()->routeIs('user.surat.*') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                            <i class="fas fa-list-check mr-2"></i>Status Surat Saya
                        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                        @endauth
                        <a href="/surat/form" 
                           class="block px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors">
                            <i class="fas fa-file-alt mr-2"></i>Pelayanan Surat
                        </a>
                    </div>
                </div>

                <!-- Laporan Dropdown (Admin Only) -->
                @if(Auth::check() && Auth::user()->role === 'admin')
                <div x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors">
                        <span class="flex items-center">
                            <i class="fas fa-chart-line mr-2"></i>
                            Laporan
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-1"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="pl-6 mt-1 space-y-1 bg-gray-50 rounded-lg py-2">
                        <a href="{{ route('laporan.rekap-surat-keluar') }}" 
                           class="block px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors {{ request()->routeIs('laporan.rekap-surat-keluar') ? 'bg-blue-50 text-[#0088cc] font-medium' : '' }}">
                            <i class="fas fa-file-export mr-2"></i>Data Rekap Surat Keluar
                        </a>
                    </div>
                </div>
                @endif

                <a href="{{ url('/') }}#kontak" 
                   class="block px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors">
                    <i class="fas fa-phone mr-2"></i>Kontak
                </a>
                
                @if(Auth::check())
                    <div class="border-t border-gray-100 mt-3 pt-3">
                        <div class="px-3 py-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#0088cc] to-blue-600 rounded-full flex items-center justify-center shadow-md">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-black">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('profile') }}" 
                               class="flex items-center px-3 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors">
                                <i class="fas fa-user-circle w-5 text-gray-500 mr-2"></i>
                                Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" @click="logout()" 
                                        class="flex items-center w-full px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt w-5 mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="border-t border-gray-100 mt-3 pt-3 px-3 space-y-2">
                        <a href="{{ route('login') }}" 
                           class="flex items-center justify-center px-4 py-2 rounded-lg text-black hover:bg-blue-50 transition-colors font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="flex items-center justify-center px-4 py-2 bg-[#0088cc] text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
                            <i class="fas fa-user-plus mr-2"></i>
                            Register
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>

<div 
    x-show="mobileMenuOpen" 
    @click="mobileMenuOpen = false"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black bg-opacity-25 z-40 lg:hidden"
    style="display: none;"
></div>

<script>
function logout() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Anda yakin ingin keluar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0088cc',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('form[action="{{ route("logout") }}"]').submit();
            }
        });
    } else {
        if (confirm('Anda yakin ingin logout?')) {
            document.querySelector('form[action="{{ route("logout") }}"]').submit();
        }
    }
}
</script>

