
@extends('layouts.app')

@section('content')
    @include('partials.navbar')


    <!-- HERO SECTION: CAROUSEL -->
    <section id="hero" class="relative h-[80vh]" x-data="{
        slides: [
            { img: '{{ asset("img/hero1.jpg") }}', title: 'Selamat Datang di Desa Ganten', subtitle: 'Website Resmi Pemerintah Desa Ganten, Kecamatan Kerjo, Kabupaten Karanganyar' },
            { img: '{{ asset("img/hero2.jpg") }}', title: 'Maju, Mandiri, Sejahtera', subtitle: 'Bersama membangun desa yang lebih baik' },
            { img: '{{ asset("img/hero3.jpg") }}', title: 'Alam & Budaya', subtitle: 'Keindahan alam dan kearifan lokal Desa Ganten' }
        ],
        active: 0,
        interval: null,
        next() { this.active = (this.active + 1) % this.slides.length },
        prev() { this.active = (this.active - 1 + this.slides.length) % this.slides.length },
        start() { this.interval = setInterval(() => { this.next() }, 4000); },
        stop() { clearInterval(this.interval); this.interval = null; }
    }" x-init="start()" @mouseenter="stop()" @mouseleave="start()">
        <!-- Slides -->
        <template x-for="(slide, idx) in slides" :key="idx">
            <div x-show="active === idx" x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0">
                <img :src="slide.img" alt="" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-[#0088cc]/80"></div>
                <div class="relative z-10 text-center text-white max-w-2xl mx-auto flex flex-col justify-center items-center h-full">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-lg" x-text="slide.title"></h1>
                    <p class="text-lg md:text-2xl mb-8 drop-shadow" x-text="slide.subtitle"></p>
                    <a href="#profil" class="inline-block bg-white text-[#0088cc] font-bold px-8 py-3 rounded-full shadow-lg hover:bg-gray-100 transition">Lihat Profil Desa</a>
                </div>
            </div>
        </template>
        <!-- Carousel Controls -->
        <button @click="prev" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 hover:bg-opacity-70 text-white rounded-full p-3 z-20 focus:outline-none">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button @click="next" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 hover:bg-opacity-70 text-white rounded-full p-3 z-20 focus:outline-none">
            <i class="fas fa-chevron-right"></i>
        </button>
        <!-- Indicators -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-2 z-20">
            <template x-for="(slide, idx) in slides" :key="idx">
                <button @click="active = idx" :class="{'bg-white': active === idx, 'bg-gray-400': active !== idx}" class="w-3 h-3 rounded-full transition-all duration-300" :style="'background-color:' + (active === idx ? '#0088cc' : '#fff')"></button>
            </template>
        </div>
    </section>

    <!-- PROFIL SINGKAT & VISI MISI -->
    <section id="profil" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <img src="{{ asset('img/profil-desa.jpg') }}" alt="Profil Desa Ganten" class="rounded-lg shadow-lg w-full mb-6 md:mb-0" />
            </div>
            <div>
                <h2 class="text-3xl font-bold mb-4 text-[#0088cc]">Profil Singkat</h2>
                <p class="mb-4 text-gray-700">Desa Ganten adalah salah satu desa di Kecamatan Kerjo, Kabupaten Karanganyar, Jawa Tengah. Desa ini memiliki potensi alam, budaya, dan sumber daya manusia yang luar biasa. Dengan semangat gotong royong, masyarakat Desa Ganten terus berupaya membangun desa yang maju, mandiri, dan sejahtera.</p>
                <h3 class="text-2xl font-bold mt-8 mb-2 text-[#0088cc]">Visi</h3>
                <p class="mb-4 italic text-gray-700">"Terwujudnya Desa Ganten yang Maju, Mandiri, dan Sejahtera Berlandaskan Gotong Royong dan Kearifan Lokal"</p>
                <h3 class="text-2xl font-bold mt-8 mb-2 text-[#0088cc]">Misi</h3>
                <ul class="list-disc pl-6 text-gray-700">
                    <li>Meningkatkan kualitas sumber daya manusia dan pelayanan publik</li>
                    <li>Mengembangkan potensi ekonomi desa berbasis pertanian dan UMKM</li>
                    <li>Melestarikan budaya dan lingkungan hidup</li>
                    <li>Meningkatkan infrastruktur dan sarana prasarana desa</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- PERANGKAT DESA -->
    <section id="perangkat" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-[#0088cc]">Perangkat Desa</h2>
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <img src="{{ asset('img/kades.jpg') }}" alt="Kepala Desa" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover shadow-lg">
                    <h3 class="font-semibold text-lg">Bapak Suharto</h3>
                    <p class="text-gray-600">Kepala Desa</p>
                </div>
                <div class="text-center">
                    <img src="{{ asset('img/sekdes.jpg') }}" alt="Sekretaris Desa" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover shadow-lg">
                    <h3 class="font-semibold text-lg">Ibu Siti Aminah</h3>
                    <p class="text-gray-600">Sekretaris Desa</p>
                </div>
                <div class="text-center">
                    <img src="{{ asset('img/kaur.jpg') }}" alt="Kaur" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover shadow-lg">
                    <h3 class="font-semibold text-lg">Bapak Slamet</h3>
                    <p class="text-gray-600">Kaur Pemerintahan</p>
                </div>
                <div class="text-center">
                    <img src="{{ asset('img/kasi.jpg') }}" alt="Kasi" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover shadow-lg">
                    <h3 class="font-semibold text-lg">Ibu Dewi</h3>
                    <p class="text-gray-600">Kasi Pelayanan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- STATISTIK SINGKAT -->
    <section id="statistik" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-[#0088cc]">Statistik Desa</h2>
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-4xl font-bold text-[#0088cc] mb-2">5.280</div>
                    <div class="text-gray-600">Jumlah Penduduk</div>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-4xl font-bold text-[#0088cc] mb-2">450 Ha</div>
                    <div class="text-gray-600">Luas Wilayah</div>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-4xl font-bold text-[#0088cc] mb-2">7</div>
                    <div class="text-gray-600">Dusun</div>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-4xl font-bold text-[#0088cc] mb-2">1945</div>
                    <div class="text-gray-600">Tahun Berdiri</div>
                </div>
            </div>
        </div>
    </section>

    <!-- LAYANAN UTAMA -->
    <section id="layanan" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-[#0088cc]">Layanan Desa</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-[#0088cc] text-white p-8 rounded-lg shadow-lg text-center hover:scale-105 transition">
                    <div class="text-5xl mb-4">📄</div>
                    <h3 class="text-xl font-semibold mb-4">Pelayanan Surat</h3>
                    <p class="mb-4">Ajukan surat keterangan secara online dengan mudah dan cepat.</p>
                    <a href="/surat" class="inline-block bg-white text-[#0088cc] px-6 py-2 rounded-lg font-bold hover:bg-gray-100 transition">Buat Surat</a>
                </div>
                <div class="bg-[#0088cc] text-white p-8 rounded-lg shadow-lg text-center hover:scale-105 transition">
                    <div class="text-5xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold mb-4">Statistik Penduduk</h3>
                    <p class="mb-4">Lihat data kependudukan Desa Ganten secara lengkap dan interaktif.</p>
                    <a href="#statistik" class="inline-block bg-white text-[#0088cc] px-6 py-2 rounded-lg font-bold hover:bg-gray-100 transition">Lihat Statistik</a>
                </div>
                <div class="bg-[#0088cc] text-white p-8 rounded-lg shadow-lg text-center hover:scale-105 transition">
                    <div class="text-5xl mb-4">🗂️</div>
                    <h3 class="text-xl font-semibold mb-4">Laporan Bulanan</h3>
                    <p class="mb-4">Akses laporan kegiatan dan keuangan desa setiap bulan.</p>
                    <a href="/laporan" class="inline-block bg-white text-[#0088cc] px-6 py-2 rounded-lg font-bold hover:bg-gray-100 transition">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </section>

    <!-- BERITA & INFORMASI -->
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

    <!-- GALERI -->
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

    <!-- KONTAK & LOKASI -->
    <section id="kontak" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-[#0088cc]">Kontak & Lokasi</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-semibold mb-4 text-[#0088cc]">Informasi Kontak</h3>
                        <div class="space-y-4">
                            <p class="flex items-center">
                                <i class="fas fa-map-marker-alt w-6 text-[#0088cc]"></i>
                                Jl. Raya Ganten No. 1, Kerjo, Karanganyar
                            </p>
                            <p class="flex items-center">
                                <i class="fas fa-phone w-6 text-[#0088cc]"></i>
                                (0271) 123456
                            </p>
                            <p class="flex items-center">
                                <i class="fas fa-envelope w-6 text-[#0088cc]"></i>
                                info@ganten-kerjo.desa.id
                            </p>
                        </div>
                        <div class="mt-6 flex space-x-4">
                            <a href="#" class="text-[#0088cc] hover:text-sky-700 transition duration-300">
                                <i class="fab fa-facebook fa-2x"></i>
                            </a>
                            <a href="#" class="text-[#0088cc] hover:text-sky-700 transition duration-300">
                                <i class="fab fa-whatsapp fa-2x"></i>
                            </a>
                            <a href="#" class="text-[#0088cc] hover:text-sky-700 transition duration-300">
                                <i class="fab fa-instagram fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
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
