@extends('layouts.app')

@section('title', 'Data Rekap Surat Keluar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Data Rekap Surat Keluar</h1>
            <p class="text-gray-600">Desa Karanganyar, Kecamatan Karangpandan, Kabupaten Karanganyar</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h2>
        <form method="GET" action="{{ route('laporan.rekap-surat-keluar') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat</label>
                <select name="jenis_surat" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="Surat Keterangan Domisili" {{ request('jenis_surat') == 'Surat Keterangan Domisili' ? 'selected' : '' }}>Surat Keterangan Domisili</option>
                    <option value="Surat Keterangan Tidak Mampu" {{ request('jenis_surat') == 'Surat Keterangan Tidak Mampu' ? 'selected' : '' }}>Surat Keterangan Tidak Mampu</option>
                    <option value="Surat Keterangan Usaha" {{ request('jenis_surat') == 'Surat Keterangan Usaha' ? 'selected' : '' }}>Surat Keterangan Usaha</option>
                    <option value="Surat Pengantar" {{ request('jenis_surat') == 'Surat Pengantar' ? 'selected' : '' }}>Surat Pengantar</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    Filter Data
                </button>
            </div>
        </form>
        
        @if(request()->hasAny(['jenis_surat', 'bulan', 'tahun']))
        <div class="mt-4 flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Menampilkan 
                @if(request('jenis_surat'))
                    <span class="font-medium">{{ request('jenis_surat') }}</span>
                @endif
                @if(request('bulan'))
                    bulan <span class="font-medium">{{ \Carbon\Carbon::create()->month(request('bulan'))->format('F') }}</span>
                @endif
                @if(request('tahun'))
                    tahun <span class="font-medium">{{ request('tahun') }}</span>
                @endif
            </div>
            <a href="{{ route('laporan.rekap-surat-keluar') }}" 
               class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-1 rounded transition duration-200">
                Reset Filter
            </a>
        </div>
        @endif
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-blue-500 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold">Total Surat</h3>
                    <p class="text-2xl font-bold">{{ $statistics['total'] ?? 0 }}</p>
                </div>
                <div class="text-3xl opacity-80">
                    📄
                </div>
            </div>
        </div>

        <div class="bg-green-500 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold">Selesai</h3>
                    <p class="text-2xl font-bold">{{ $statistics['selesai'] ?? 0 }}</p>
                </div>
                <div class="text-3xl opacity-80">
                    ✅
                </div>
            </div>
        </div>

        <div class="bg-yellow-500 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold">Diproses</h3>
                    <p class="text-2xl font-bold">{{ $statistics['diproses'] ?? 0 }}</p>
                </div>
                <div class="text-3xl opacity-80">
                    ⏳
                </div>
            </div>
        </div>

        <div class="bg-gray-500 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold">Pending</h3>
                    <p class="text-2xl font-bold">{{ $statistics['pending'] ?? 0 }}</p>
                </div>
                <div class="text-3xl opacity-80">
                    ⌛
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Surat Keluar</h2>
            <p class="text-sm text-gray-600 mt-1">Menampilkan {{ $rekapSurat->total() }} data surat keluar</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemohon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keperluan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rekapSurat as $index => $rekap)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rekapSurat->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rekap->tanggal_surat_formatted }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rekap->nomor_surat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $rekap->nama_pemohon }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $rekap->jenis_surat }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs">
                                {{ Str::limit($rekap->untuk_keperluan, 50) }}
                                @if(strlen($rekap->untuk_keperluan) > 50)
                                    <button onclick="showFullText('{{ addslashes($rekap->untuk_keperluan) }}')" 
                                            class="text-blue-600 hover:text-blue-800 text-xs ml-1">
                                        Lihat lebih
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rekap->status_badge_class }}">
                                {{ ucfirst($rekap->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada data surat keluar</p>
                                <p class="text-sm">Belum ada surat yang tercatat dalam periode yang dipilih</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rekapSurat->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $rekapSurat->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Export Data</h2>
        <p class="text-gray-600 mb-4">Unduh data rekap surat keluar dalam format yang Anda inginkan</p>
        <div class="flex space-x-3">
            <button onclick="exportToPDF()" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </button>
            <button onclick="exportToExcel()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </button>
        </div>
    </div>
</div>

<div id="textModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Keperluan</h3>
            <p id="fullTextContent" class="text-gray-700 mb-6"></p>
            <div class="flex justify-end">
                <button onclick="closeTextModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showFullText(text) {
    document.getElementById('fullTextContent').textContent = text;
    document.getElementById('textModal').classList.remove('hidden');
}

function closeTextModal() {
    document.getElementById('textModal').classList.add('hidden');
}

function exportToPDF() {
    // Build URL with current filters
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'pdf');
    
    window.open(`{{ route('laporan.rekap-surat-keluar') }}?${params.toString()}`, '_blank');
}

function exportToExcel() {
    // Build URL with current filters  
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    
    window.open(`{{ route('laporan.rekap-surat-keluar') }}?${params.toString()}`, '_blank');
}
</script>
@endpush
@endsection
