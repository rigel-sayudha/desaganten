<!-- Navbar -->
<nav class="fixed w-full z-50" style="background-color: #0088cc;">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-8">
                <a href="/" class="flex items-center space-x-3">
                    <!-- Logo circle -->
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                        <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-10 h-10 rounded-full object-cover">
                    </div>
                    <!-- Title with subtitle -->
                    <div class="flex flex-col text-white">
                        <span class="font-bold text-xl leading-tight">DESA GANTEN</span>
                        <span class="text-sm">KERJO - KARANGANYAR</span>
                    </div>
                </a>
                <div class="hidden md:flex space-x-6 items-center">
                    <a href="{{ url('/') }}" class="text-white hover:text-sky-200 py-2 px-3">Beranda</a>
                    <a href="{{ url('/') }}#profil" class="text-white hover:text-sky-200 py-2 px-3">Profil Desa</a>
                    

                    <!-- Statistik Penduduk Dropdown -->
                    <div class="relative group">
                        <button class="text-white hover:text-sky-200 py-2 px-3 inline-flex items-center group/dropbtn">
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
                        <button class="text-white hover:text-sky-200 py-2 px-3 inline-flex items-center group/dropbtn">
                            Pelayanan Surat
                            <i class="fas fa-chevron-down ml-2 transition-transform duration-300 group-hover/dropbtn:rotate-180"></i>
                        </button>
                        <div class="absolute hidden group-hover:block w-48 bg-white text-gray-700 shadow-lg py-2 rounded-lg"
                             style="top: 100%; margin-top: 0.5rem;">
                            <div class="absolute h-4 w-full -top-4"></div>
                            <a href="/surat/status" class="block px-4 py-2 hover:bg-sky-100">Status Layanan</a>
                            <a href="/surat/jadwal" class="block px-4 py-2 hover:bg-sky-100">Jadwal Pengambilan</a>
                            <a href="/surat/form" class="block px-4 py-2 hover:bg-sky-100">Pengisian Surat</a>
                        </div>
                    </div>


                    <!-- Laporan Dropdown -->
                    <div class="relative group">
                        <button class="text-white hover:text-sky-200 py-2 px-3 inline-flex items-center group/dropbtn">
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

                    <a href="{{ url('/') }}#kontak" class="text-white hover:text-sky-200 py-2 px-3">Kontak</a>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button class="mobile-menu-button" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl text-white"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#beranda" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Beranda</a>
                <a href="#profil" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Profil Desa</a>
                
                <!-- Mobile Statistik Penduduk -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 text-white hover:bg-sky-700 rounded flex justify-between items-center">
                        Statistik Penduduk
                        <i class="fas fa-chevron-down text-white"></i>
                    </button>
                    <div class="hidden pl-4">
                        <a href="/statistik/wilayah" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Wilayah</a>
                        <a href="/statistik/usia" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Usia</a>
                        <a href="/statistik/pendidikan" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Pendidikan</a>
                        <a href="/statistik/pekerjaan" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Pekerjaan</a>
                    </div>
                </div>

                <!-- Mobile Pelayanan Surat -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 text-white hover:bg-sky-700 rounded flex justify-between items-center">
                        Pelayanan Surat
                        <i class="fas fa-chevron-down text-white"></i>
                    </button>
                    <div class="hidden pl-4">
                        <a href="/surat/status" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Status Layanan</a>
                        <a href="/surat/jadwal" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Jadwal Pengambilan</a>
                        <a href="/surat/form" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Pengisian Surat</a>
                    </div>
                </div>

                <!-- Mobile Laporan -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left px-3 py-2 text-white hover:bg-sky-700 rounded flex justify-between items-center">
                        Laporan
                        <i class="fas fa-chevron-down text-white"></i>
                    </button>
                    <div class="hidden pl-4">
                        <a href="/laporan/bulanan" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Laporan Bulanan</a>
                        <a href="/laporan/rekap" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Rekap Bulanan</a>
                    </div>
                </div>

                <a href="#kontak" class="block px-3 py-2 text-white hover:bg-sky-700 rounded">Kontak</a>
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
</script>
