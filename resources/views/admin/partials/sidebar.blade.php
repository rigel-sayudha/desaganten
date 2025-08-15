<!-- Admin Sidebar -->
<aside id="adminSidebar" x-data="{ open: false, minimized: false }" :class="{'translate-x-0': open, '-translate-x-full': !open}" class="bg-white shadow-lg h-screen w-64 fixed top-16 left-0 z-40 pt-0 md:translate-x-0 -translate-x-full md:block transition-all duration-300" style="transition-property: transform;">
    <div class="flex flex-col items-center justify-center py-6 border-b">
        <div class="w-16 h-16 bg-[#0088cc] rounded-full flex items-center justify-center mb-2">
            <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-14 h-14 rounded-full object-cover">
        </div>
        <span class="font-bold text-[#0088cc] text-lg text-center leading-tight sidebar-text">DESA GANTEN</span>
    </div>
    <button id="sidebarToggle" class="absolute -right-4 top-6 bg-[#0088cc] text-white rounded-full w-8 h-8 flex items-center justify-center shadow-md focus:outline-none transition-transform duration-300 z-50 hidden md:flex" title="Expand/Minimize Sidebar">
        <i class="fas fa-angle-double-left" id="sidebarToggleIcon"></i>
    </button>
    <button @click="open = !open" class="md:hidden fixed top-4 left-4 z-50 bg-[#0088cc] text-white rounded-full w-10 h-10 flex items-center justify-center shadow-md focus:outline-none transition-transform duration-300" title="Buka Sidebar">
        <i class="fas fa-bars"></i>
    </button>
    <div class="flex flex-col h-full pt-4">
<nav class="flex-1 px-4 py-2 space-y-2" x-data="{ openStat: false, openSurat: false, openLap: false }">
            <a href="/admin/dashboard" class="flex items-center px-4 py-3 rounded-lg text-[#0088cc] font-semibold hover:bg-blue-50 transition">
                <i class="fas fa-tachometer-alt mr-3"></i> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="#profil" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">
                <i class="fas fa-user mr-3"></i> <span class="sidebar-text">Profil Desa</span>
            </a>

            <div class="relative">
                <button @click="openStat = !openStat" class="flex items-center w-full px-4 py-3 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition focus:outline-none">
                    <i class="fas fa-chart-bar mr-3"></i> <span class="sidebar-text flex-1 text-left">Statistik Penduduk</span>
                    <i class="fas fa-chevron-down ml-auto" :class="{'rotate-180': openStat}" x-show="!minimized"></i>
                </button>
                <div x-show="openStat" class="ml-8 mt-1 space-y-1" @click.away="openStat = false" x-cloak>
                    <a href="/admin/wilayah" class="block px-4 py-2 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">Wilayah</a>
                    <a href="/statistik/usia" class="block px-4 py-2 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">Usia</a>
                    <a href="/statistik/pendidikan" class="block px-4 py-2 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">Pendidikan</a>
                    <a href="/statistik/pekerjaan" class="block px-4 py-2 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">Pekerjaan</a>
                </div>
            </div>

            <div class="relative">
                <button @click="openSurat = !openSurat" class="flex items-center w-full px-4 py-3 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition focus:outline-none">
                    <i class="fas fa-envelope-open-text mr-3"></i> <span class="sidebar-text flex-1 text-left">Pelayanan Surat</span>
                    <i class="fas fa-chevron-down ml-auto" :class="{'rotate-180': openSurat}" x-show="!minimized"></i>
                </button>
                <div x-show="openSurat" class="ml-8 mt-1 space-y-1" @click.away="openSurat = false" x-cloak>
                    <a href="/surat/status" class="block px-4 py-2 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">Status Layanan</a>
                    <a href="/surat/jadwal" class="block px-4 py-2 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">Jadwal Pengambilan</a>
                    <a href="/admin/surat" class="block px-4 py-2 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">Pengelolaan Surat</a>
                </div>
            </div>

            <div class="relative">
                <button @click="openLap = !openLap" class="flex items-center w-full px-4 py-3 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition focus:outline-none">
                    <i class="fas fa-file-alt mr-3"></i> <span class="sidebar-text flex-1 text-left">Laporan</span>
                    <i class="fas fa-chevron-down ml-auto" :class="{'rotate-180': openLap}" x-show="!minimized"></i>
                </button>
                <div x-show="openLap" class="ml-8 mt-1 space-y-1" @click.away="openLap = false" x-cloak>
                    <a href="/laporan/bulanan" class="block px-4 py-2 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">Laporan Bulanan</a>
                    <a href="/laporan/rekap" class="block px-4 py-2 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">Rekap Bulanan</a>
                </div>
            </div>

            <a href="/admin/user" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">
                <i class="fas fa-users mr-3"></i> <span class="sidebar-text">Data User</span>
            </a>
            <a href="#kontak" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-[#0088cc] hover:bg-blue-50 transition">
                <i class="fas fa-phone mr-3"></i> <span class="sidebar-text">Kontak</span>
            </a>
        </nav>
        <div class="px-4 pb-6">
            <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm" class="inline">
                @csrf
                <button type="button" id="logoutBtn" class="flex items-center text-sm text-gray-400 hover:text-red-600 transition">
                    <i class="fas fa-sign-out-alt mr-2"></i> <span class="sidebar-text">Logout</span>
                </button>
            </form>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.getElementById('logoutBtn').addEventListener('click', function(e) {
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Logout',
                                text: 'Anda telah berhasil logout sebagai admin.',
                                confirmButtonColor: '#0088cc',
                            }).then(() => {
                                document.getElementById('logoutForm').submit();
                            });
                        }
                    });
                });
            </script>
        </div>

    <div x-show="open" @click="open = false" class="fixed inset-0 bg-black bg-opacity-40 z-30 md:hidden" x-cloak></div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('sidebarToggleIcon');
        const main = document.getElementById('adminMain');
        const navbar = document.getElementById('adminNavbar');
        let minimized = false;
        function setMainPadding() {
            if (main) {
                if (window.innerWidth >= 768) {
                    main.style.paddingLeft = minimized ? '5rem' : '16rem';
                } else {
                    main.style.paddingLeft = '0';
                }
            }
            if (navbar) {
                if (window.innerWidth >= 768) {
                    navbar.style.marginLeft = minimized ? '5rem' : '16rem';
                } else {
                    navbar.style.marginLeft = '0';
                }
            }
        }
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                minimized = !minimized;
                if (minimized) {
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-20');
                    sidebar.querySelectorAll('.sidebar-text, span.font-bold').forEach(el => {
                        el.style.display = 'none';
                    });
                    toggleIcon.classList.remove('fa-angle-double-left');
                    toggleIcon.classList.add('fa-angle-double-right');
                } else {
                    sidebar.classList.add('w-64');
                    sidebar.classList.remove('w-20');
                    sidebar.querySelectorAll('.sidebar-text, span.font-bold').forEach(el => {
                        el.style.display = '';
                    });
                    toggleIcon.classList.add('fa-angle-double-left');
                    toggleIcon.classList.remove('fa-angle-double-right');
                }
                setMainPadding();
            });
        }
        window.addEventListener('resize', setMainPadding);
        setMainPadding();
    });
</script>
