@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Pengelolaan Surat', 'url' => '#'],
];
$jenisSurat = [
    'domisili' => 'Surat Keterangan Domisili',
    'ktp' => 'Surat Keterangan KTP',
    'kk' => 'Surat Keterangan KK',
    'skck' => 'Surat Pengantar SKCK',
    'kematian' => 'Surat Keterangan Kematian',
    'kelahiran' => 'Surat Keterangan Kelahiran',
];
@endphp
@include('admin.partials.navbar')
<div id="adminLayout" class="flex min-h-screen bg-gray-50">
    @include('admin.partials.sidebar')
    <main id="adminMain" class="flex-1 pt-24 pb-8 px-2 sm:px-4 md:px-8 transition-all duration-300">
        <div class="max-w-7xl mx-auto w-full">
            <h1 class="text-2xl md:text-3xl font-bold text-[#0088cc] mb-8 text-center md:text-left">Pengelolaan Surat Keterangan</h1>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <div class="md:w-1/2">
                    <label for="jenisSuratSelect" class="block font-semibold mb-2">Pilih Jenis Surat Keterangan:</label>
                    <select id="jenisSuratSelect" class="border rounded px-3 py-2 w-full max-w-xs">
                        <option value="">-- Pilih Jenis Surat --</option>
                        @foreach($jenisSurat as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="dataPreviewContainer" class="overflow-x-auto">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <table class="min-w-full w-full bg-white rounded border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="px-4 py-2 text-left">No</th>
                                <th class="px-4 py-2 text-left">Nama Pemohon</th>
                                <th class="px-4 py-2 text-left">Jenis Surat</th>
                                <th class="px-4 py-2 text-left">Tanggal Pengajuan</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="suratTableBody">
                            @foreach($suratList as $surat)
                                <tr class="border-b" data-jenis="{{ $surat->jenis_surat }}">
                                    <td class="py-2 px-4">{{ $surat->id }}</td>
                                    <td class="py-2 px-4">{{ $surat->nama_pemohon ?? $surat->nama_lengkap ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $surat->jenis_surat ?? 'KTP' }}</td>
                                    <td class="py-2 px-4">{{ $surat->created_at ? $surat->created_at->format('Y-m-d') : '-' }}</td>
                                    <td class="py-2 px-4">{{ $surat->status ?? '-' }}</td>
                                    <td class="py-2 px-4">
                                        <a href="{{ route('admin.surat.edit', $surat->id) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                                        <form action="{{ route('admin.surat.destroy', $surat->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus surat ini?')">Hapus</button>
                                        </form>
                                        <a href="/admin/surat/print-pdf/{{ $surat->jenis_surat }}/{{ $surat->id }}" target="_blank" class="ml-2 inline-block bg-green-600 text-white px-3 py-1 rounded shadow hover:bg-green-700 transition" title="Print PDF">
                                            <i class="fas fa-print"></i> Print
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@include('admin.partials.footer')
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('jenisSuratSelect');
    const tbody = document.getElementById('suratTableBody');

    function loadSuratData(jenis = '') {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Memuat data...</td></tr>';
        
        const url = jenis ? `/admin/surat/data?jenis=${jenis}` : window.location.href;
        
        if (jenis) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    displaySuratData(data, jenis);
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-500">Gagal memuat data.</td></tr>';
                });
        } else {
            location.reload();
        }
    }

    function displaySuratData(data, jenis) {
        tbody.innerHTML = '';
        
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Tidak ada data surat untuk jenis yang dipilih.</td></tr>';
            return;
        }

        data.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'border-b';
            row.setAttribute('data-jenis', jenis);
            
            const nama = item.nama || item.nama_pemohon || item.nama_lengkap || '-';
            const status = item.status_pengajuan || item.status || '-';
            const tanggal = item.tanggal_pengajuan || '-';
            const jenisSuratDisplay = getJenisSuratDisplay(jenis);
            
            row.innerHTML = `
                <td class="py-2 px-4">${item.id}</td>
                <td class="py-2 px-4">${nama}</td>
                <td class="py-2 px-4">${jenisSuratDisplay}</td>
                <td class="py-2 px-4">${tanggal}</td>
                <td class="py-2 px-4">${status}</td>
                <td class="py-2 px-4">
                    <a href="/admin/surat/${item.id}/edit" class="text-blue-600 hover:underline mr-2">Edit</a>
                    <button onclick="deleteSurat(${item.id})" class="text-red-600 hover:underline mr-2">Hapus</button>
                    <a href="/admin/surat/print-pdf/${jenis}/${item.id}" target="_blank" 
                       class="ml-2 inline-block bg-green-600 text-white px-3 py-1 rounded shadow hover:bg-green-700 transition" 
                       title="Print PDF">
                        <i class="fas fa-print"></i> Print
                    </a>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function getJenisSuratDisplay(jenis) {
        const jenisSuratMap = {
            'domisili': 'Surat Keterangan Domisili',
            'ktp': 'Surat Keterangan KTP',
            'kk': 'Surat Keterangan KK',
            'skck': 'Surat Pengantar SKCK',
            'kematian': 'Surat Keterangan Kematian',
            'kelahiran': 'Surat Keterangan Kelahiran'
        };
        return jenisSuratMap[jenis] || jenis.toUpperCase();
    }

    function deleteSurat(id) {
        if (confirm('Yakin ingin menghapus surat ini?')) {
            fetch(`/admin/surat/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload current filter
                    loadSuratData(select.value);
                } else {
                    alert('Gagal menghapus surat');
                }
            })
            .catch(() => {
                alert('Gagal menghapus surat');
            });
        }
    }

    select.addEventListener('change', function() {
        loadSuratData(this.value);
    });

    window.deleteSurat = deleteSurat;
});
</script>
@endpush
