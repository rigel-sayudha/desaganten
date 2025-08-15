<!-- Footer -->
<footer class="bg-[#0088cc] text-white py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <h4 class="text-xl font-semibold mb-4">Desa Ganten</h4>
                <p class="text-blue-100">Website resmi Pemerintah Desa Ganten</p>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-4">Link Cepat</h4>
                <ul class="space-y-2">
                    <li><a href="#beranda" class="text-blue-100 hover:text-white transition duration-300">Beranda</a></li>
                    <li><a href="#profil" class="text-blue-100 hover:text-white transition duration-300">Profil Desa</a></li>
                    <li><a href="#layanan" class="text-blue-100 hover:text-white transition duration-300">Layanan</a></li>
                    <li><a href="#kontak" class="text-blue-100 hover:text-white transition duration-300">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-4">Jam Operasional</h4>
                <p class="text-blue-100">Senin - Jumat: 08.00 - 16.00</p>
                <p class="text-blue-100">Sabtu: 08.00 - 12.00</p>
                <p class="text-blue-100 mb-4">Minggu: Tutup</p>
                <div class="flex flex-col space-y-3">
                    <div class="bg-white bg-opacity-10 border border-blue-200 rounded-lg px-6 py-4 inline-block text-left shadow">
                        <div class="text-xs text-blue-100 mb-1">Visitor Hari Ini</div>
                        <div class="text-2xl font-bold text-white">{{ $visitorToday ?? 0 }}</div>
                    </div>
                    <div class="bg-white bg-opacity-10 border border-blue-200 rounded-lg px-6 py-4 inline-block text-left shadow">
                        <div class="text-xs text-blue-100 mb-1">Total Visitor</div>
                        <div class="text-2xl font-bold text-white">{{ $visitorAllTime ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-blue-300 mt-8 pt-8 text-center">
            <p class="text-blue-100">&copy; {{ date('Y') }} Desa Ganten. All rights reserved.</p>
        </div>
    </div>
</footer>
