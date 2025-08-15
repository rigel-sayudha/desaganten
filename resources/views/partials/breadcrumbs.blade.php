@props(['items'])
<nav class="flex items-center space-x-2 text-sm bg-white rounded-lg shadow px-4 py-2 mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center text-gray-500">
        @foreach ($items as $i => $item)
            @if ($i === 0)
                <li>
                    <a href="{{ $item['url'] }}" class="text-[#0088cc] hover:text-sky-600 flex items-center">
                        <i class="fas fa-home mr-1"></i> {{ $item['label'] }}
                    </a>
                </li>
            @else
                <li>
                    <span class="text-gray-400 mx-2">/</span>
                    @if (!$loop->last)
                        <a href="{{ $item['url'] }}" class="text-[#0088cc] hover:text-sky-600">{{ $item['label'] }}</a>
                    @else
                        <span class="text-gray-500 font-semibold">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endif
        @endforeach
    </ol>
</nav>
