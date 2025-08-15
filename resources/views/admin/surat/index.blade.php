@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Pengelolaan Surat', 'url' => '#'],
];
$jenisSurat = [
    'domisili' => 'Surat Keterangan Domisili',
    'usaha' => 'Surat Keterangan Usaha',
    'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
@include('admin.partials.navbar')
<div id="adminLayout" class="flex min-h-screen bg-gray-50">
    @include('admin.partials.sidebar')
    <main id="adminMain" class="flex-1 ml-0 md:ml-64 pt-24 pb-8 px-4 md:px-8 transition-all duration-300">
        <div class="max-w-5xl ml-0 md:ml-8">
            <h1 class="text-2xl font-bold text-[#0088cc] mb-6">Pengelolaan Surat Keterangan</h1>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <div class="sm:w-1/2">
                    <label class="block font-semibold mb-2">Pilih Jenis Surat Keterangan:</label>
                    <select id="jenisSuratSelect" class="border rounded px-3 py-2 w-full max-w-xs">
                        <option value="">-- Pilih Jenis Surat --</option>
                        @foreach($jenisSurat as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="dataPreviewContainer" class="overflow-x-auto">
                <table id="suratTable" class="min-w-full bg-white rounded shadow border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="px-4 py-2 border-b">No</th>
                            <th class="px-4 py-2 border-b">Nama Pemohon</th>
                            <th class="px-4 py-2 border-b">Jenis Surat</th>
                            <th class="px-4 py-2 border-b">Tanggal Pengajuan</th>
                            <th class="px-4 py-2 border-b">Status</th>
                            <th class="px-4 py-2 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="suratTableBody">
                        <tr>
                            <td colspan="6" class="text-gray-400 text-center py-12">Silakan pilih jenis surat untuk melihat data pengajuan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@include('admin.partials.footer')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('jenisSuratSelect');
        const tableBody = document.getElementById('suratTableBody');
        select.addEventListener('change', function() {
            const jenis = select.value;
            if(!jenis) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-gray-400 text-center py-12">Silakan pilih jenis surat untuk melihat data pengajuan.</td></tr>';
                return;
            }
            let endpoint = '';
            if(jenis === 'domisili') {
                endpoint = '/admin/surat/preview-domisili';
            } else {
                endpoint = `/admin/surat/preview-data/${jenis}`;
            }
            fetch(endpoint)
                .then(res => res.json())
                .then(data => {
                    if(!Array.isArray(data) || data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="6" class="text-gray-400 text-center py-12">Tidak ada data pengajuan untuk jenis surat ini.</td></tr>';
                        return;
                    }
                    tableBody.innerHTML = data.map((item, idx) => {
                        if(jenis === 'domisili') {
                            return `
                                <tr>
                                    <td class='px-4 py-2 border-b text-center'>${idx+1}</td>
                                    <td class='px-4 py-2 border-b'>${item.nama}</td>
                                    <td class='px-4 py-2 border-b'>Surat Keterangan Domisili</td>
                                    <td class='px-4 py-2 border-b'>${item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-'}</td>
                                    <td class='px-4 py-2 border-b'>${item.status_pengajuan}</td>
                                    <td class='px-4 py-2 border-b text-center'>
<a href="#" class="text-blue-600 hover:underline" onclick="showDetailModal(${item.id}, '${item.nama}', '${item.nik}', '${item.tempat_lahir}', '${item.tanggal_lahir}', '${item.jenis_kelamin}', '${item.kewarganegaraan}', '${item.agama}', '${item.status}', '${item.pekerjaan}', '${item.alamat}', '${item.keperluan}', '${item.created_at}', '${item.status_pengajuan}')">Detail</a>
                                        <a href="/admin/surat/preview-pdf/domisili/${item.id}" target="_blank" class="ml-2 text-green-600 hover:underline">Preview PDF</a>
                                    </td>
                                </tr>
                            `;
                        } else {
                            return `
                                <tr>
                                    <td class='px-4 py-2 border-b text-center'>${idx+1}</td>
                                    <td class='px-4 py-2 border-b'>${item.nama_pemohon}</td>
                                    <td class='px-4 py-2 border-b'>${item.jenis_surat}</td>
                                    <td class='px-4 py-2 border-b'>${item.tanggal_pengajuan}</td>
                                    <td class='px-4 py-2 border-b'>${item.status}</td>
                                    <td class='px-4 py-2 border-b text-center'>
                                        <a href="/admin/surat/detail/${item.id}" class="text-blue-600 hover:underline">Detail</a>
                                    </td>
                                </tr>
                            `;
                        }
                    }).join('');
                })
                .catch(() => {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-red-500 text-center py-12">Gagal memuat data.</td></tr>';
                });
        });
    });

    // Modal HTML
    const modalHtml = `
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden transition-opacity duration-300">
        <div id="detailModalContent" class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative transform scale-95 opacity-0 transition-all duration-300">
            <button id="closeDetailModal" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">&times;</button>
            <h2 class="text-xl font-bold mb-4 text-[#0088cc]">Detail Surat Keterangan Domisili</h2>
            <table class="w-full mb-4">
                <tbody id="detailModalBody"></tbody>
            </table>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const detailModal = document.getElementById('detailModal');
    const closeDetailModal = document.getElementById('closeDetailModal');
    closeDetailModal.addEventListener('click', function() {
        const modalContent = document.getElementById('detailModalContent');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        detailModal.classList.remove('opacity-100');
        detailModal.classList.add('opacity-0');
        setTimeout(() => {
            detailModal.classList.add('hidden');
        }, 300);
    });

    window.showDetailModal = function(id, nama, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, kewarganegaraan, agama, status, pekerjaan, alamat, keperluan, created_at, status_pengajuan) {
        const body = document.getElementById('detailModalBody');
        body.innerHTML = `
            <tr><td class="font-semibold w-40">Nama</td><td>: ${nama}</td></tr>
            <tr><td class="font-semibold">NIK</td><td>: ${nik}</td></tr>
            <tr><td class="font-semibold">Tempat/Tgl Lahir</td><td>: ${tempat_lahir}, ${tanggal_lahir}</td></tr>
            <tr><td class="font-semibold">Jenis Kelamin</td><td>: ${jenis_kelamin}</td></tr>
            <tr><td class="font-semibold">Kewarganegaraan</td><td>: ${kewarganegaraan}</td></tr>
            <tr><td class="font-semibold">Agama</td><td>: ${agama}</td></tr>
            <tr><td class="font-semibold">Status</td><td>: ${status}</td></tr>
            <tr><td class="font-semibold">Pekerjaan</td><td>: ${pekerjaan}</td></tr>
            <tr><td class="font-semibold">Alamat</td><td>: ${alamat}</td></tr>
            <tr><td class="font-semibold">Keperluan</td><td>: ${keperluan}</td></tr>
            <tr><td class="font-semibold">Tanggal Pengajuan</td><td>: ${created_at ? new Date(created_at).toLocaleDateString('id-ID') : '-'}</td></tr>
            <tr><td class="font-semibold">Status Pengajuan</td><td>: ${status_pengajuan}</td></tr>
        `;
        detailModal.classList.remove('hidden');
        // Animasi masuk
        setTimeout(() => {
            detailModal.classList.remove('opacity-0');
            detailModal.classList.add('opacity-100');
            const modalContent = document.getElementById('detailModalContent');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
</script>
@endsection
