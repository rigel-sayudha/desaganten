
@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif


    <!-- HERO SECTION: CAROUSEL -->
    @php
        $hero1 = asset('img/hero1.jpg');
        $hero2 = asset('img/hero2.jpg');
        $hero3 = asset('img/hero3.jpg');
    @endphp
    <section id="hero" class="relative h-[60vh] sm:h-[70vh] md:h-[80vh] flex items-center justify-center overflow-hidden bg-gray-200"
        x-data="carouselData()"
        x-init="start()"
        @mouseenter="stop()"
        @mouseleave="start()"
    >
        <template x-for="(slide, idx) in slides" :key="idx">
            <div x-show="active === idx" x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 h-full w-full flex items-center justify-center">
                <img :src="slide.img" alt="" class="w-full h-full object-cover absolute inset-0" />
                <div class="relative z-10 text-center text-white max-w-xl sm:max-w-2xl mx-auto flex flex-col justify-center items-center h-full px-2 sm:px-4">
                    <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold mb-2 sm:mb-4 drop-shadow-lg leading-tight" x-text="slide.title"></h1>
                    <p class="text-base sm:text-lg md:text-2xl mb-4 sm:mb-8 drop-shadow" x-text="slide.subtitle"></p>
                    <a href="#profil" class="inline-block bg-white text-[#0088cc] font-bold px-4 sm:px-8 py-2 sm:py-3 rounded-full shadow-lg hover:bg-gray-100 transition text-sm sm:text-base">Lihat Profil Desa</a>
                </div>
            </div>
        </template>
        <button @click="prev" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-80 text-white rounded-full p-2 sm:p-4 z-30 focus:outline-none shadow-lg">
            <i class="fas fa-chevron-left text-lg sm:text-2xl"></i>
        </button>
        <button @click="next" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-80 text-white rounded-full p-2 sm:p-4 z-30 focus:outline-none shadow-lg">
            <i class="fas fa-chevron-right text-lg sm:text-2xl"></i>
        </button>
        <!-- Indicators -->
        <div class="absolute bottom-4 sm:bottom-8 left-1/2 -translate-x-1/2 flex space-x-2 sm:space-x-3 z-30">
            <template x-for="(slide, idx) in slides" :key="idx">
                <button @click="active = idx" :class="{'ring-2 ring-white scale-125': active === idx, 'opacity-60': active !== idx}" class="w-3 h-3 sm:w-4 sm:h-4 rounded-full transition-all duration-300 bg-white border-2 border-[#0088cc]"></button>
            </template>
        </div>
    </section>

    <!-- LOGO & BRAND IDENTITY -->
    <section class="py-8 sm:py-12 bg-gradient-to-r from-[#0088cc] to-blue-500 text-white">
        <div class="max-w-7xl mx-auto px-2 sm:px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 items-center">
                <!-- Logo Karanganyar -->
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center justify-center w-32 h-32 bg-white rounded-full shadow-lg p-2">
                        <img src="/img/logo-karanganyar.png" alt="Logo Karanganyar" class="w-full h-full object-contain">
                    </div>
                </div>
                
                <!-- Branding Text -->
                <div class="text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-2">DESA GANTEN</h2>
                    <p class="text-lg sm:text-xl font-semibold mb-2">Kecamatan Kerjo</p>
                    <p class="text-base sm:text-lg opacity-90">Kabupaten Karanganyar, Jawa Tengah</p>
                    <p class="mt-4 italic text-sm sm:text-base opacity-80">"Maju, Mandiri, Sejahtera"</p>
                </div>
                
                <!-- Logo Desa -->
                <div class="text-center md:text-right">
                    <div class="inline-flex items-center justify-center w-32 h-32 bg-white rounded-full shadow-lg p-2">
                        <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROFIL SINGKAT & VISI MISI -->
    <section id="profil" class="py-10 sm:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-12 items-center">
            <!--<div>
                <img src="{{ asset('img/profil-desa.jpg') }}" alt="Profil Desa Ganten" class="rounded-lg shadow-lg w-full mb-4 sm:mb-6 md:mb-0 max-h-64 sm:max-h-none object-cover" />
            </div> -->
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold mb-2 sm:mb-4 text-[#0088cc]">Profil Singkat</h2>
                <p class="mb-2 sm:mb-4 text-gray-700 text-sm sm:text-base">Desa Ganten adalah salah satu desa di Kecamatan Kerjo, Kabupaten Karanganyar, Jawa Tengah. Desa ini memiliki potensi alam, budaya, dan sumber daya manusia yang luar biasa. Dengan semangat gotong royong, masyarakat Desa Ganten terus berupaya membangun desa yang maju, mandiri, dan sejahtera.</p>
                <h3 class="text-lg sm:text-2xl font-bold mt-4 sm:mt-8 mb-1 sm:mb-2 text-[#0088cc]">Visi</h3>
                <p class="mb-2 sm:mb-4 italic text-gray-700 text-sm sm:text-base">"MEMAJUKAN DESA GANTEN YANG ADIL DAN MERATA"</p>
                <h3 class="text-lg sm:text-2xl font-bold mt-4 sm:mt-8 mb-1 sm:mb-2 text-[#0088cc]">Misi</h3>
                <ul class="list-disc pl-4 sm:pl-6 text-gray-700 text-sm sm:text-base">
                    <li>Melanjutkan program pemerintahan desa sebelumnya yang belum terselesaikan</li>
                    <li>Memfungsikan lembaga yang ada sesuai tugas pokok dan fungsinya</li>
                    <li>Memfungsikan tim pelaksana kegiatan (TPK) sesuai bidangnya</li>
                    <li>Mempermudah pelayanan terhadap warga masyarakat</li>
                    <li>Mengembangkan usaha ekonomi kerakyatan lewat kelompok</li>
                    <li>Menumbuh kembangkan hidup gotong royong dan toleran saling menghormati</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- PERANGKAT DESA -->
    <section id="perangkat" class="py-10 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-2 sm:px-4">
            <h2 class="text-2xl sm:text-3xl font-bold text-center mb-6 sm:mb-12 text-[#0088cc]">Perangkat Desa</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8">
                <div class="text-center">
                    <img src="{{ asset('storage/perdes/Munadi.jpeg') }}" alt="Kepala Desa" class="w-20 h-20 sm:w-32 sm:h-32 rounded-full mx-auto mb-2 sm:mb-4 object-cover shadow-lg">
                    <h3 class="font-semibold text-base sm:text-lg">Munadi</h3>
                    <p class="text-gray-600 text-xs sm:text-base">Kepala Desa</p>
                </div>
                <div class="text-center">
                    <img src="{{ asset('storage/perdes/Kartika Dyah Ayu S. (sekretaris).jpeg') }}" alt="Sekretaris Desa" class="w-20 h-20 sm:w-32 sm:h-32 rounded-full mx-auto mb-2 sm:mb-4 object-cover shadow-lg">
                    <h3 class="font-semibold text-base sm:text-lg">Kartika Dyah Ayu S.</h3>
                    <p class="text-gray-600 text-xs sm:text-base">Sekretaris Desa</p>
                </div>
                <div class="text-center">
                    <img src="{{ asset('storage/perdes/Sukiman (pemerintahan).jpeg') }}" alt="Kaur" class="w-20 h-20 sm:w-32 sm:h-32 rounded-full mx-auto mb-2 sm:mb-4 object-cover shadow-lg">
                    <h3 class="font-semibold text-base sm:text-lg">Sukiman</h3>
                    <p class="text-gray-600 text-xs sm:text-base">Kaur Pemerintahan</p>
                </div>
                <div class="text-center">
                    <img src="{{ asset('storage/perdes/winarni (pelayanan).jpeg') }}" alt="Kasi" class="w-20 h-20 sm:w-32 sm:h-32 rounded-full mx-auto mb-2 sm:mb-4 object-cover shadow-lg">
                    <h3 class="font-semibold text-base sm:text-lg">Winarno</h3>
                    <p class="text-gray-600 text-xs sm:text-base">Kasi Pelayanan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- STATISTIK SINGKAT 
    <section id="statistik" class="py-10 sm:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-2 sm:px-4">
            <h2 class="text-2xl sm:text-3xl font-bold text-center mb-6 sm:mb-12 text-[#0088cc]">Statistik Desa</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8 text-center">
                <div class="bg-white p-4 sm:p-8 rounded-lg shadow-lg">
                    <div class="text-2xl sm:text-4xl font-bold text-[#0088cc] mb-1 sm:mb-2">5.280</div>
                    <div class="text-gray-600 text-xs sm:text-base">Jumlah Penduduk</div>
                </div>
                <div class="bg-white p-4 sm:p-8 rounded-lg shadow-lg">
                    <div class="text-2xl sm:text-4xl font-bold text-[#0088cc] mb-1 sm:mb-2">450 Ha</div>
                    <div class="text-gray-600 text-xs sm:text-base">Luas Wilayah</div>
                </div>
                <div class="bg-white p-4 sm:p-8 rounded-lg shadow-lg">
                    <div class="text-2xl sm:text-4xl font-bold text-[#0088cc] mb-1 sm:mb-2">7</div>
                    <div class="text-gray-600 text-xs sm:text-base">Dusun</div>
                </div>
                <div class="bg-white p-4 sm:p-8 rounded-lg shadow-lg">
                    <div class="text-2xl sm:text-4xl font-bold text-[#0088cc] mb-1 sm:mb-2">1945</div>
                    <div class="text-gray-600 text-xs sm:text-base">Tahun Berdiri</div>
                </div>
            </div>
        </div>
    </section>
-->
    <!-- LAYANAN UTAMA -->
    <section id="layanan" class="py-10 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-2 sm:px-4">
          <h2 class="text-2xl sm:text-3xl font-bold text-center mb-6 sm:mb-12 text-[#0088cc]">Layanan Desa</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-8">
                <div class="bg-[#0088cc] text-white p-4 sm:p-8 rounded-lg shadow-lg text-center hover:scale-105 transition">
                    <div class="text-3xl sm:text-5xl mb-2 sm:mb-4">📄</div>
                    <h3 class="text-base sm:text-xl font-semibold mb-2 sm:mb-4">Pelayanan Surat</h3>
                    <p class="mb-2 sm:mb-4 text-xs sm:text-base">Ajukan surat keterangan secara online dengan mudah dan cepat.</p>
                    <a href="/surat/form" class="inline-block bg-white text-[#0088cc] px-4 sm:px-6 py-1.5 sm:py-2 rounded-lg font-bold hover:bg-gray-100 transition text-xs sm:text-base">Buat Surat</a>
                </div>
                <div class="bg-[#0088cc] text-white p-4 sm:p-8 rounded-lg shadow-lg text-center hover:scale-105 transition">
                    <div class="text-3xl sm:text-5xl mb-2 sm:mb-4">📊</div>
                    <h3 class="text-base sm:text-xl font-semibold mb-2 sm:mb-4">Statistik Penduduk</h3>
                    <p class="mb-2 sm:mb-4 text-xs sm:text-base">Lihat data kependudukan Desa Ganten secara lengkap dan interaktif.</p>
                    <a href="/statistik" class="inline-block bg-white text-[#0088cc] px-4 sm:px-6 py-1.5 sm:py-2 rounded-lg font-bold hover:bg-gray-100 transition text-xs sm:text-base">Lihat Statistik</a>
                </div>
            </div>
        </div>
    </section>

    <!-- BERITA & INFORMASI 
    <section id="berita" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-[#0088cc]">Berita & Informasi</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:scale-105 transition">
                    <img src="{{ asset('img/berita1.jpg') }}" alt="Berita 1" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 mb-2">1 Agustus 2025</div>
                        <h3 class="font-semibold text-xl mb-2">Jadwal Posyandu Bulan Agustus</h3>
                        <p class="text-gray-600 mb-4">Kegiatan Posyandu akan dilaksanakan pada tanggal 10 Agustus 2025...</p>
                        <a href="#" class="inline-block bg-[#0088cc] text-white px-4 py-2 rounded hover:bg-sky-700 transition">Baca selengkapnya</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:scale-105 transition">
                    <img src="{{ asset('img/berita2.jpg') }}" alt="Berita 2" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 mb-2">28 Juli 2025</div>
                        <h3 class="font-semibold text-xl mb-2">Gotong Royong Pembangunan Jalan Desa</h3>
                        <p class="text-gray-600 mb-4">Warga desa bersama-sama bergotong royong dalam pembangunan...</p>
                        <a href="#" class="inline-block bg-[#0088cc] text-white px-4 py-2 rounded hover:bg-sky-700 transition">Baca selengkapnya</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:scale-105 transition">
                    <img src="{{ asset('img/berita3.jpg') }}" alt="Berita 3" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 mb-2">25 Juli 2025</div>
                        <h3 class="font-semibold text-xl mb-2">Program Vaksinasi COVID-19</h3>
                        <p class="text-gray-600 mb-4">Program vaksinasi COVID-19 tahap kedua akan dilaksanakan...</p>
                        <a href="#" class="inline-block bg-[#0088cc] text-white px-4 py-2 rounded hover:bg-sky-700 transition">Baca selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
-->
    <!-- GALERI 
    <section id="galeri" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-[#0088cc]">Galeri Desa</h2>
            <div class="grid md:grid-cols-4 gap-4">
                <div class="rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('img/galeri1.jpg') }}" alt="Galeri 1" class="w-full h-48 object-cover hover:scale-110 transition duration-300">
                </div>
                <div class="rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('img/galeri2.jpg') }}" alt="Galeri 2" class="w-full h-48 object-cover hover:scale-110 transition duration-300">
                </div>
                <div class="rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('img/galeri3.jpg') }}" alt="Galeri 3" class="w-full h-48 object-cover hover:scale-110 transition duration-300">
                </div>
                <div class="rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('img/galeri4.jpg') }}" alt="Galeri 4" class="w-full h-48 object-cover hover:scale-110 transition duration-300">
                </div>
            </div>
        </div>
    </section>
-->
     <!--INFORMASI WILAYAH & LOKASI  -->
    <section id="kontak" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-[#0088cc]">Informasi Wilayah & Lokasi</h2>
            <!--
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-semibold mb-4 text-[#0088cc]">Informasi Wilayah</h3>
                        <table class="w-full text-gray-700 mb-4">
                            <tr>
                                <td class="font-semibold py-2 pr-4">Kode PUM</td>
                                <td class="py-2">3313162003</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-2 pr-4">Tahun Pembentukan</td>
                                <td class="py-2">-</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-2 pr-4">Dasar Hukum</td>
                                <td class="py-2">-</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-2 pr-4">Luas Wilayah</td>
                                <td class="py-2">-</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-2 pr-4">Batas Sebelah Utara</td>
                                <td class="py-2">-</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-2 pr-4">Batas Sebelah Selatan</td>
                                <td class="py-2">-</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-2 pr-4">Batas Sebelah Timur</td>
                                <td class="py-2">-</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-2 pr-4">Batas Sebelah Barat</td>
                                <td class="py-2">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                -->
                <div class="h-96 rounded-lg overflow-hidden shadow-lg">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.9999999999995!2d111.00000000000001!3d-7.500000000000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMzAnMDAuMCJTIDExMcKwMDAnMDAuMCJF!5e0!3m2!1sen!2sid!4v1627981258190!5m2!1sen!2sid" 
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

    @include('partials.footer')

    <!-- Back to top button -->
    <button id="backToTop" class="fixed bottom-4 right-4 bg-[#0088cc] text-white p-3 rounded-full shadow-lg hover:bg-sky-700 transition duration-300 hidden">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Back to Top Button
        const backToTopButton = document.getElementById('backToTop');
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
    </script>

@endsection
