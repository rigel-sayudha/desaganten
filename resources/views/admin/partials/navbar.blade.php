<!-- Admin Navbar -->
<nav id="adminNavbar" class="w-full bg-[#0088cc] text-white shadow-lg px-6 py-4 flex justify-between items-center transition-all duration-300" x-data>
    <div class="flex items-center space-x-3">
        <!-- Hamburger for mobile -->
        <button class="md:hidden mr-3 focus:outline-none" @click="window.document.getElementById('adminSidebar').__x.$data.open = true">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <img src="/img/logo.png" alt="Logo" class="w-10 h-10 rounded-full bg-white p-1">
        <span class="font-bold text-lg tracking-wide">Admin Desa Ganten</span>
    </div>
    <div class="flex items-center space-x-6">
        <span class="hidden md:inline">Hai, Admin!</span>
        <div class="relative" style="display: inline-block;">
            <a href="#" id="adminProfileBtn" class="hover:text-sky-200 flex items-center">
                <i class="fas fa-user-circle text-2xl"></i>
            </a>
            <div id="adminProfileDropdown" class="absolute right-0 mt-2 w-40 bg-white text-gray-700 rounded shadow-lg py-2 z-50" style="display: none;">
                <span class="block px-4 py-2 font-semibold">Admin</span>
                <form method="POST" action="{{ route('admin.logout') }}" id="navbarLogoutForm" class="inline">
                    @csrf
                    <button type="button" id="navbarLogoutBtn" class="w-full text-left px-4 py-2 hover:bg-sky-100 hover:text-[#0088cc] bg-transparent border-none p-0 m-0">Logout</button>
                </form>
            </div>
            <script>
                const profileBtn = document.getElementById('adminProfileBtn');
                const profileDropdown = document.getElementById('adminProfileDropdown');
                profileBtn.addEventListener('mouseenter', function() {
                    profileDropdown.style.display = 'block';
                });
                profileBtn.addEventListener('mouseleave', function() {
                    setTimeout(function() {
                        if (!profileDropdown.matches(':hover')) {
                            profileDropdown.style.display = 'none';
                        }
                    }, 100);
                });
                profileDropdown.addEventListener('mouseenter', function() {
                    profileDropdown.style.display = 'block';
                });
                profileDropdown.addEventListener('mouseleave', function() {
                    profileDropdown.style.display = 'none';
                });
            </script>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('navbarLogoutBtn').addEventListener('click', function(e) {
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
            });
        </script>
    </div>
</nav>
