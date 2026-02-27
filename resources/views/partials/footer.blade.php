<!-- Modern Footer -->
<footer class="bg-gradient-to-br from-[#0088cc] to-blue-600 text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent transform -skew-y-1"></div>
    </div>
    
    <div class="relative">
        <!-- Main Footer Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Desa Info Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                            <img src="/img/logo.png" alt="Logo Desa Ganten" class="w-10 h-10 rounded-full object-cover">
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">DESA GANTEN</h3>
                            <p class="text-blue-100 text-sm">KERJO - KARANGANYAR</p>
                        </div>
                    </div>
                    <p class="text-blue-100 leading-relaxed mb-6 max-w-md">
                        Website resmi Pemerintah Desa Ganten untuk memberikan pelayanan terbaik kepada masyarakat dengan sistem digital yang modern dan terpercaya.
                    </p>
                    
                    <!-- Contact Info -->
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-sm"></i>
                            </div>
                            <span class="text-blue-100 text-sm">Jl. Desa Ganten, Kerjo, Karanganyar</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone text-sm"></i>
                            </div>
                            <span class="text-blue-100 text-sm">0812-2356-7890</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <span class="text-blue-100 text-sm">gantendesaku@gmail.com</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 flex items-center">
                        <div class="w-1 h-6 bg-white rounded-full mr-3"></div>
                        Link Cepat
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ url('/') }}" class="text-blue-100 hover:text-white transition-colors duration-200 flex items-center group">
                                <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}#profil" class="text-blue-100 hover:text-white transition-colors duration-200 flex items-center group">
                                <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                Profil Desa
                            </a>
                        </li>
                        <li>
                            <a href="/surat/form" class="text-blue-100 hover:text-white transition-colors duration-200 flex items-center group">
                                <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                Pelayanan Surat
                            </a>
                        </li>
                        <li>
                            <a href="/statistik/wilayah" class="text-blue-100 hover:text-white transition-colors duration-200 flex items-center group">
                                <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                Statistik
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}#kontak" class="text-blue-100 hover:text-white transition-colors duration-200 flex items-center group">
                                <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                Kontak
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Visitor Stats & Schedule Section -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 flex items-center">
                        <div class="w-1 h-6 bg-white rounded-full mr-3"></div>
                        Informasi
                    </h4>
                    
                    <!-- Operating Hours -->
                    <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4 mb-6 border border-white border-opacity-20">
                        <h5 class="font-medium mb-3 flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            Jam Operasional
                        </h5>
                        <div class="space-y-2 text-sm text-blue-100">
                            <div class="flex justify-between">
                                <span>Senin - Kamis</span>
                                <span>08:00 - 15:30</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Jumat</span>
                                <span>08:00 - 11:30</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Sabtu - Minggu</span>
                                <span>Libur</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Visitor Statistics -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4 text-center border border-white border-opacity-20">
                            <div class="text-2xl font-bold text-white mb-1">{{ $visitorToday ?? 0 }}</div>
                            <div class="text-xs text-blue-100">Visitor Hari Ini</div>
                        </div>
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4 text-center border border-white border-opacity-20">
                            <div class="text-2xl font-bold text-white mb-1">{{ $visitorAllTime ?? 0 }}</div>
                            <div class="text-xs text-blue-100">Total Visitor</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="border-t border-white border-opacity-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                    <div class="text-center md:text-left">
                        <p class="text-blue-100 text-sm">
                            &copy; {{ date('Y') }} Desa Ganten. All rights reserved.
                        </p>
                    </div>
                    
                    <!-- Social Media Links -->
                    <div class="flex items-center space-x-4">
                        <span class="text-blue-100 text-sm">Ikuti Kami:</span>
                        <div class="flex space-x-3">
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors duration-200">
                                <i class="fab fa-facebook-f text-sm"></i>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors duration-200">
                                <i class="fab fa-instagram text-sm"></i>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors duration-200">
                                <i class="fab fa-youtube text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
