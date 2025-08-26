<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="icon" type="image/png" href="/img/logo.png" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>GANTEN | Website Desa Ganten</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <style>
            [x-cloak] { display: none !important; }
            html {
                scroll-behavior: smooth;
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="loadingOverlay" class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-80 hidden">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-12 w-12 text-[#0088cc] mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-[#0088cc] font-semibold text-lg">Memuat...</span>
            </div>
        </div>
        @if (!Str::startsWith(request()->path(), 'admin'))
            @hasSection('topbar')
                @yield('topbar')
            @else
                @include('partials.topbar')
            @endif
        @endif
        @yield('content')
        @include('admin.partials.alpinejs')
        <script>
            const loadingOverlay = document.getElementById('loadingOverlay');
            window.addEventListener('beforeunload', function () {
                loadingOverlay.classList.remove('hidden');
            });
            window.addEventListener('DOMContentLoaded', function () {
                loadingOverlay.classList.add('hidden');
            });

            const backToTopButton = document.getElementById('backToTop');
            if (backToTopButton) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 300) {
                        backToTopButton.classList.remove('hidden');
                    } else {
                        backToTopButton.classList.add('hidden');
                    }
                });
                backToTopButton.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        </script>
    </body>
</html>
