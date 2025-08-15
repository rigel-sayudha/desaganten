<!-- Navbar -->
<nav id="main-navbar" class="fixed w-full z-50 transition-colors duration-500 bg-transparent" style="background-color: transparent;">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-8">
                <a href="/" class="flex items-center space-x-3">
                    <!-- Logo circle -->
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                        <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-10 h-10 rounded-full object-cover">
                    </div>
                    <!-- Title with subtitle -->
                    <div class="flex flex-col text-black">
                        <span class="font-bold text-xl leading-tight">DESA GANTEN</span>
                        <span class="text-sm">KERJO - KARANGANYAR</span>
                    </div>
                </a>
                <div class="hidden md:flex space-x-6 items-center">
                    <a href="{{ url('/') }}" class="text-[#0088cc] hover:text-sky-700 py-2 px-3">Beranda</a>
                    <a href="{{ url('/') }}#profil" class="text-[#0088cc] hover:text-sky-700 py-2 px-3">Profil Desa</a>
                    

                    <!-- Statistik Penduduk Dropdown -->
                    <div class="relative group">
                        <button class="text-[#0088cc] hover:text-sky-700 py-2 px-3 inline-flex items-center group/dropbtn">
                            Statistik Penduduk
                            <i class="fas fa-chevron-down ml-2 transition-transform duration-300 group-hover/dropbtn:rotate-180"></i>
                        </button>
                        <div class="absolute hidden group-hover:block w-48 bg-white text-gray-700 shadow-lg py-2 rounded-lg"
                             style="top: 100%; margin-top: 0.5rem;">
                            <div class="absolute h-4 w-full -top-4"></div>
                            <a href="/statistik/wilayah" class="block px-4 py-2 hover:bg-sky-100">Wilayah</a>
                            <a href="/statistik/usia" class="block px-4 py-2 hover:bg-sky-100">Usia</a>
                            <a href="/statistik/pendidikan" class="block px-4 py-2 hover:bg-sky-100">Pendidikan</a>
                            <a href="/statistik/pekerjaan" class="block px-4 py-2 hover:bg-sky-100">Pekerjaan</a>
                        </div>
                    </div>


                    <!-- Pelayanan Surat Dropdown -->
                    <div class="relative group">
                        <button class="text-[#0088cc] hover:text-sky-700 py-2 px-3 inline-flex items-center group/dropbtn">
                            Pelayanan Surat
                            <i class="fas fa-chevron-down ml-2 transition-transform duration-300 group-hover/dropbtn:rotate-180"></i>
                        </button>
                        <div class="absolute hidden group-hover:block w-48 bg-white text-gray-700 shadow-lg py-2 rounded-lg"
                             style="top: 100%; margin-top: 0.5rem;">
                            <div class="absolute h-4 w-full -top-4"></div>
                            <a href="/surat/status" class="block px-4 py-2 hover:bg-sky-100">Status Layanan</a>
                            <a href="/surat/jadwal" class="block px-4 py-2 hover:bg-sky-100">Jadwal Pengambilan</a>
                            <a href="/surat/form" class="block px-4 py-2 hover:bg-sky-100">Pelayanan Surat</a>
                        </div>
                    </div>


                    <!-- Laporan Dropdown -->
                    <div class="relative group">
                        <button class="text-[#0088cc] hover:text-sky-700 py-2 px-3 inline-flex items-center group/dropbtn">
                            Laporan
                            <i class="fas fa-chevron-down ml-2 transition-transform duration-300 group-hover/dropbtn:rotate-180"></i>
                        </button>
                        <div class="absolute hidden group-hover:block w-48 bg-white text-gray-700 shadow-lg py-2 rounded-lg"
                             style="top: 100%; margin-top: 0.5rem;">
                            <div class="absolute h-4 w-full -top-4"></div>
                            <a href="/laporan/bulanan" class="block px-4 py-2 hover:bg-sky-100">Laporan Bulanan</a>
                            <a href="/laporan/rekap" class="block px-4 py-2 hover:bg-sky-100">Rekap Bulanan</a>
                        </div>
                    </div>

                    <a href="{{ url('/') }}#kontak" class="text-[#0088cc] hover:text-sky-700 py-2 px-3">Kontak</a>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if(Auth::check())
                    <div class="relative" style="display: inline-block;">
                        <button id="userDropdownBtn" class="flex items-center space-x-2 bg-white text-[#0088cc] font-bold px-4 py-2 rounded-full shadow hover:bg-gray-100 transition text-sm">
                            <i class="fas fa-user-circle text-xl"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-2 transition-transform duration-300"></i>
                        </button>
                        <div id="userDropdownMenu" class="absolute right-0 w-40 bg-white text-gray-700 shadow-lg py-2 rounded-lg z-50" style="top: 100%; margin-top: 0.5rem; display: none;">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-sky-100">Profil</a>
                        <form method="POST" action="{{ route('logout') }}" id="userLogoutForm">
                            @csrf
                            <button type="button" id="userLogoutBtn" class="w-full text-left px-4 py-2 hover:bg-sky-100">Logout</button>
                        </form>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            document.getElementById('userLogoutBtn').addEventListener('click', function(e) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil Logout',
                                    text: 'Anda telah berhasil logout.',
                                    confirmButtonColor: '#0088cc',
                                }).then(() => {
                                    document.getElementById('userLogoutForm').submit();
                                });
                            });
                        </script>
                        </div>
                        <script>
                            const userBtn = document.getElementById('userDropdownBtn');
                            const userMenu = document.getElementById('userDropdownMenu');
                            userBtn.addEventListener('mouseenter', function() {
                                userMenu.style.display = 'block';
                            });
                            userBtn.addEventListener('mouseleave', function() {
                                setTimeout(function() {
                                    if (!userMenu.matches(':hover')) {
                                        userMenu.style.display = 'none';
                                    }
                                }, 100);
                            });
                            userMenu.addEventListener('mouseenter', function() {
                                userMenu.style.display = 'block';
                            });
                            userMenu.addEventListener('mouseleave', function() {
                                userMenu.style.display = 'none';
                            });
                        </script>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-[#0088cc] font-bold px-4 py-2 rounded-full shadow hover:bg-gray-100 transition text-sm">Login</a>
                    <a href="{{ route('register') }}" class="bg-[#006fa1] text-white font-bold px-4 py-2 rounded-full shadow hover:bg-[#005a8c] transition text-sm">Register</a>
                @endif
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button class="mobile-menu-button" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl text-white"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-white" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#beranda" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Beranda</a>
                <a href="#profil" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Profil Desa</a>
                <!-- Mobile Statistik Penduduk -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded flex justify-between items-center">
                        Statistik Penduduk
                        <i class="fas fa-chevron-down text-black"></i>
                    </button>
                    <div class="hidden pl-4">
                        <a href="/statistik/wilayah" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Wilayah</a>
                        <a href="/statistik/usia" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Usia</a>
                        <a href="/statistik/pendidikan" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Pendidikan</a>
                        <a href="/statistik/pekerjaan" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Pekerjaan</a>
                    </div>
                </div>
                <!-- Mobile Pelayanan Surat -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded flex justify-between items-center">
                        Pelayanan Surat
                        <i class="fas fa-chevron-down text-black"></i>
                    </button>
                    <div class="hidden pl-4">
                        <a href="/surat/status" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Status Layanan</a>
                        <a href="/surat/jadwal" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Jadwal Pengambilan</a>
                        <a href="/surat/form" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Pelayanan Surat</a>
                    </div>
                </div>
                <!-- Mobile Laporan -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded flex justify-between items-center">
                        Laporan
                        <i class="fas fa-chevron-down text-black"></i>
                    </button>
                    <div class="hidden pl-4">
                        <a href="/laporan/bulanan" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Laporan Bulanan</a>
                        <a href="/laporan/rekap" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Rekap Bulanan</a>
                    </div>
                </div>
                <a href="#kontak" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Kontak</a>
                @if(Auth::check())
                    <div class="border-t mt-2 pt-2">
                        <a href="{{ route('profile') }}" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-black hover:bg-sky-100 hover:text-[#0088cc] rounded">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-white bg-[#006fa1] hover:bg-[#005a8c] rounded">Register</a>
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- Add this script at the bottom of your navbar partial -->
<script>
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    }

    // Handle mobile dropdowns
    document.querySelectorAll('.mobile-dropdown button').forEach(button => {
        button.addEventListener('click', () => {
            const dropdownContent = button.nextElementSibling;
            dropdownContent.classList.toggle('hidden');
            // Toggle chevron rotation
            const chevron = button.querySelector('.fa-chevron-down');
            chevron.style.transform = dropdownContent.classList.contains('hidden') 
                ? 'rotate(0deg)' 
                : 'rotate(180deg)';
        });
    });

    // Dynamic transparent navbar on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('main-navbar');
        if (window.scrollY > 30) {
            navbar.classList.remove('bg-transparent');
            navbar.classList.add('bg-white');
            navbar.style.backgroundColor = '#fff';
            navbar.classList.add('shadow-lg');
        } else {
            navbar.classList.add('bg-transparent');
            navbar.classList.remove('bg-white');
            navbar.style.backgroundColor = 'transparent';
            navbar.classList.remove('shadow-lg');
        }
    });
</script>
