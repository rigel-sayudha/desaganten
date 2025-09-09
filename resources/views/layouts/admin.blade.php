<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="/img/logo.png" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | GANTEN Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: false }" x-init="
    Alpine.store('sidebar', {
        isOpen: false,
        toggle() { this.isOpen = !this.isOpen },
        open() { this.isOpen = true },
        close() { this.isOpen = false }
    })
">
    <div class="flex h-screen overflow-hidden">>
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            @include('admin.partials.sidebar')
        </div>
        
        <!-- Sidebar overlay for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 hidden lg:hidden"></div>
        
        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <!-- Mobile menu button -->
                        <button @click="$store.sidebar.toggle()" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Page title for mobile -->
                        <h1 class="text-xl font-semibold text-gray-900 lg:hidden">
                            @yield('title', 'Dashboard')
                        </h1>
                        
                        <!-- Desktop: Breadcrumbs/Title -->
                        <div class="hidden lg:block">
                            <nav class="flex" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                                    <li>
                                        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">
                                            <i class="fas fa-home mr-1"></i>
                                            Dashboard
                                        </a>
                                    </li>
                                    @hasSection('breadcrumb')
                                        @yield('breadcrumb')
                                    @endif
                                </ol>
                            </nav>
                        </div>
                        
                        <!-- User menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <span class="hidden md:block text-gray-700 font-medium">{{ Auth::user()->name ?? 'Admin' }}</span>
                                <i class="fas fa-chevron-down ml-2 text-gray-400"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit mr-2"></i>
                                    Profile
                                </a>
                                <a href="/" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Lihat Website
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('warning'))
                        <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span>{{ session('warning') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>{{ session('info') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Mobile menu script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            }
            
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }
            
            mobileMenuButton.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });
            
            // Close sidebar when clicking on a link (mobile)
            sidebar.addEventListener('click', function(event) {
                if (event.target.tagName === 'A' && window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });
        
        // Auto-dismiss flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
