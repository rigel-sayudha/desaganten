@props(['items'])

<!-- Modern Breadcrumbs -->
<nav 
    class="flex items-center justify-between bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-xl shadow-sm px-4 py-3 mb-6" 
    aria-label="Breadcrumb"
>
    <!-- Breadcrumb Trail -->
    <ol class="flex items-center space-x-2 text-sm">
        @foreach ($items as $i => $item)
            @if ($i === 0)
                <!-- Home Link -->
                <li>
                    <a 
                        href="{{ $item['url'] }}" 
                        class="flex items-center space-x-1 text-[#0088cc] hover:text-blue-600 transition-colors duration-200 font-medium group"
                    >
                        <div class="w-7 h-7 bg-[#0088cc] bg-opacity-10 rounded-lg flex items-center justify-center group-hover:bg-opacity-20 transition-colors">
                            <i class="fas fa-home text-xs"></i>
                        </div>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @else
                <!-- Separator & Link -->
                <li class="flex items-center space-x-2">
                    <!-- Modern Separator -->
                    <div class="w-5 h-5 flex items-center justify-center">
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    </div>
                    
                    @if (!$loop->last)
                        <!-- Intermediate Link -->
                        <a 
                            href="{{ $item['url'] }}" 
                            class="text-gray-600 hover:text-[#0088cc] transition-colors duration-200 font-medium px-2 py-1 rounded-lg hover:bg-blue-50"
                        >
                            {{ $item['label'] }}
                        </a>
                    @else
                        <!-- Current Page -->
                        <span class="text-gray-800 font-semibold px-3 py-1 bg-white rounded-lg shadow-sm border border-gray-200">
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>
            @endif
        @endforeach
    </ol>

    <!-- Back Button -->
    @if(count($items) > 1)
        <button 
            onclick="history.back()" 
            class="flex items-center space-x-2 text-gray-600 hover:text-[#0088cc] transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-white group"
            title="Kembali"
        >
            <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
            </div>
            <span class="text-sm font-medium hidden sm:block">Kembali</span>
        </button>
    @endif
</nav>
