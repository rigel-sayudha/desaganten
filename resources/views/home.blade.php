<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Desa Sukamaju - Website Resmi</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        
        <!-- Tailwind CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    </head>
    <body class="antialiased">
        <!-- Navbar -->
        <nav class="bg-green-800 text-white fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-8">
                        <a href="/" class="flex items-center space-x-2">
                            <span class="font-bold text-xl">Desa Sukamaju</span>
                        </a>
                        <div class="hidden md:flex space-x-6">
                            <a href="#beranda" class="hover:text-green-200">Beranda</a>
                            <a href="#profil" class="hover:text-green-200">Profil Desa</a>
                            <a href="#statistik" class="hover:text-green-200">Statistik</a>
                            <a href="#layanan" class="hover:text-green-200">Layanan</a>
                            <a href="#kontak" class="hover:text-green-200">Kontak</a>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="/login" class="hover:text-green-200">Masuk</a>
                        <a href="/register" class="bg-white text-green-800 px-4 py-2 rounded-lg hover:bg-green-100">Daftar</a>
                    </div>
                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button class="mobile-menu-button">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="beranda" class="relative h-screen">
            <div class="absolute inset-0">
                <img src="{{ asset('img/hero-bg.jpg') }}" alt="Desa Sukamaju" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black opacity-50"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 h-full flex flex-col justify-center items-center text-white text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-4">Desa Sukamaju</h1>
                <p class="text-xl md:text-2xl mb-8">"Maju, Mandiri, dan Sejahtera"</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#statistik" class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-lg font-semibold transition duration-300">
                        <i class="fas fa-chart-bar mr-2"></i>Statistik Penduduk
                    </a>
                    <a href="#layanan" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold transition duration-300">
                        <i class="fas fa-file-alt mr-2"></i>Ajukan Surat
                    </a>
                    <a href="#kontak" class="bg-yellow-600 hover:bg-yellow-700 px-6 py-3 rounded-lg font-semibold transition duration-300">
                        <i class="fas fa-phone mr-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
        </section>

        <!-- Profil Singkat -->
        <section id="profil" class="py-16 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Profil Desa Sukamaju</h2>
                <div class="grid md:grid-cols-3 gap-8 mb-12">
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                        <div class="text-4xl font-bold text-green-800 mb-2">1945</div>
                        <div class="text-gray-600">Tahun Berdiri</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                        <div class="text-4xl font-bold text-green-800 mb-2">5.280</div>
                        <div class="text-gray-600">Jumlah Penduduk</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                        <div class="text-4xl font-bold text-green-800 mb-2">450 Ha</div>
                        <div class="text-gray-600">Luas Wilayah</div>
                    </div>
                </div>
                
                <!-- Perangkat Desa -->
                <h3 class="text-2xl font-bold text-center mb-8">Perangkat Desa</h3>
                <div class="grid md:grid-cols-4 gap-6">
                    <div class="text-center transform hover:scale-105 transition duration-300">
                        <img src="{{ asset('img/kades.jpg') }}" alt="Kepala Desa" class="w-48 h-48 rounded-full mx-auto mb-4 object-cover shadow-lg">
                        <h3 class="font-semibold">Bapak Suharto</h3>
                        <p class="text-gray-600">Kepala Desa</p>
                    </div>
                    <!-- Add more village officials here -->
                </div>
            </div>
        </section>

        <!-- Fitur Utama -->
        <section id="layanan" class="py-16">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Layanan Utama</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                        <div class="text-5xl mb-4">📊</div>
                        <h3 class="text-xl font-semibold mb-4">Statistik Penduduk</h3>
                        <p class="text-gray-600 mb-4">Data lengkap kependudukan Desa Sukamaju</p>
                        <a href="#statistik" class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                            Lihat Data
                        </a>
                    </div>
                    <div class="bg-white p-8 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                        <div class="text-5xl mb-4">📝</div>
                        <h3 class="text-xl font-semibold mb-4">Pelayanan Surat</h3>
                        <p class="text-gray-600 mb-4">Ajukan surat keterangan secara online</p>
                        <a href="/surat" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            Buat Surat
                        </a>
                    </div>
                    <div class="bg-white p-8 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                        <div class="text-5xl mb-4">📂</div>
                        <h3 class="text-xl font-semibold mb-4">Laporan Bulanan</h3>
                        <p class="text-gray-600 mb-4">Laporan kegiatan dan keuangan desa</p>
                        <a href="/laporan" class="inline-block bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition duration-300">
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Berita & Informasi -->
        <section id="berita" class="py-16 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Berita & Informasi Terbaru</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg overflow-hidden shadow-lg transform hover:scale-105 transition duration-300">
                        <img src="{{ asset('img/berita1.jpg') }}" alt="Berita 1" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <div class="text-sm text-gray-500 mb-2">1 Agustus 2025</div>
                            <h3 class="font-semibold text-xl mb-2">Jadwal Posyandu Bulan Agustus</h3>
                            <p class="text-gray-600 mb-4">Kegiatan Posyandu akan dilaksanakan pada tanggal 10 Agustus 2025...</p>
                            <a href="#" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300">
                                Baca selengkapnya
                            </a>
                        </div>
                    </div>
                    <!-- Add more news items here -->
                </div>
            </div>
        </section>

        <!-- Galeri -->
        <section id="galeri" class="py-16">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Galeri Desa</h2>
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset('img/galeri1.jpg') }}" alt="Galeri 1" 
                             class="w-full h-48 object-cover transform hover:scale-110 transition duration-300">
                    </div>
                    <!-- Add more gallery items here -->
                </div>
            </div>
        </section>

        <!-- Kontak dan Lokasi -->
        <section id="kontak" class="py-16 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Kontak & Lokasi</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <div class="bg-white p-6 rounded-lg shadow-lg">
                            <h3 class="text-xl font-semibold mb-4">Informasi Kontak</h3>
                            <div class="space-y-4">
                                <p class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-6 text-green-600"></i>
                                    Jl. Raya Sukamaju No. 123, Kec. Sukamaju
                                </p>
                                <p class="flex items-center">
                                    <i class="fas fa-phone w-6 text-green-600"></i>
                                    (021) 1234-5678
                                </p>
                                <p class="flex items-center">
                                    <i class="fas fa-envelope w-6 text-green-600"></i>
                                    info@desasukamaju.desa.id
                                </p>
                            </div>
                            <div class="mt-6 flex space-x-4">
                                <a href="#" class="text-blue-600 hover:text-blue-800 transition duration-300">
                                    <i class="fab fa-facebook fa-2x"></i>
                                </a>
                                <a href="#" class="text-green-600 hover:text-green-800 transition duration-300">
                                    <i class="fab fa-whatsapp fa-2x"></i>
                                </a>
                                <a href="#" class="text-pink-600 hover:text-pink-800 transition duration-300">
                                    <i class="fab fa-instagram fa-2x"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="h-96 rounded-lg overflow-hidden shadow-lg">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.952912260219!2d3.375295414770757!3d6.5276316952457235!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMzEnMzkuNSJOIDPCsDIyJzMxLjEiRQ!5e0!3m2!1sen!2sid!4v1627981258190!5m2!1sen!2sid" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-green-800 text-white py-8">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-xl font-semibold mb-4">Desa Sukamaju</h4>
                        <p class="text-green-200">Website resmi Pemerintah Desa Sukamaju</p>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold mb-4">Link Cepat</h4>
                        <ul class="space-y-2">
                            <li><a href="#beranda" class="text-green-200 hover:text-white transition duration-300">Beranda</a></li>
                            <li><a href="#profil" class="text-green-200 hover:text-white transition duration-300">Profil Desa</a></li>
                            <li><a href="#layanan" class="text-green-200 hover:text-white transition duration-300">Layanan</a></li>
                            <li><a href="#kontak" class="text-green-200 hover:text-white transition duration-300">Kontak</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold mb-4">Jam Operasional</h4>
                        <p class="text-green-200">Senin - Jumat: 08.00 - 16.00</p>
                        <p class="text-green-200">Sabtu: 08.00 - 12.00</p>
                        <p class="text-green-200">Minggu: Tutup</p>
                    </div>
                </div>
                <div class="border-t border-green-700 mt-8 pt-8 text-center">
                    <p class="text-green-200">&copy; {{ date('Y') }} Desa Sukamaju. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Back to top button -->
        <button id="backToTop" class="fixed bottom-4 right-4 bg-green-600 text-white p-3 rounded-full shadow-lg hover:bg-green-700 transition duration-300 hidden">
            <i class="fas fa-arrow-up"></i>
        </button>

        <script>
            // Back to top button
            const backToTopButton = document.getElementById('backToTop');
            
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 100) {
                    backToTopButton.classList.remove('hidden');
                } else {
                    backToTopButton.classList.add('hidden');
                }
            });

            backToTopButton.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // Smooth scrolling for anchor links
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
