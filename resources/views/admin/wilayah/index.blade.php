@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Wilayah', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
@include('admin.partials.alpinejs')
@include('admin.partials.navbar')
<div id="adminLayout" x-data="{ sidebarOpen: false }" class="flex flex-col md:flex-row min-h-screen bg-gray-50 transition-all duration-300">
    @include('admin.partials.sidebar')
    <main class="flex-1 ml-0 md:ml-64 min-h-screen bg-gray-50 pt-20 pb-8 px-2 sm:px-4 md:px-8 transition-all duration-300 w-full">
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl font-bold text-[#0088cc]">Kelola Data Wilayah</h1>
            <button onclick="openWilayahModal()" class="bg-[#0088cc] text-white px-4 py-2 rounded hover:bg-[#006fa1] font-semibold">Tambah Data Wilayah</button>
        </div>
        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif
        <div class="overflow-x-auto bg-white rounded-lg shadow-lg min-h-[400px] flex flex-col justify-between transition-all duration-300">
            <table class="min-w-full divide-y divide-blue-200 text-sm sm:text-base">
                <thead class="bg-[#0088cc]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Nama/Kelompok/Jenis</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Jumlah Data</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">L</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">P</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-blue-100 min-h-[320px]">
                    @forelse($wilayah as $w)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-700 align-middle">{{ $w->nama }}</td>
                        <td class="px-6 py-4 text-center text-gray-700 align-middle">{{ $w->jumlah }}</td>
                        <td class="px-6 py-4 text-center text-blue-700 font-bold align-middle">{{ $w->laki_laki }}</td>
                        <td class="px-6 py-4 text-center text-pink-700 font-bold align-middle">{{ $w->perempuan }}</td>
                        <td class="px-6 py-4 text-center align-middle">
                            <button class="text-[#0088cc] font-bold mr-2" onclick="openWilayahModal({{ $w->id }}, '{{ addslashes($w->nama) }}', {{ $w->jumlah }}, {{ $w->laki_laki }}, {{ $w->perempuan }})">Edit</button>
                            <form action="{{ route('admin.wilayah.destroy', $w->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-600 font-bold btn-hapus">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-16 text-gray-400 align-middle">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-database fa-3x mb-4 text-blue-100"></i>
                                <span class="text-lg">Belum ada data wilayah.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal Wilayah (Tambah/Edit) -->
        <div id="wilayahModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 opacity-0 pointer-events-none transition-opacity duration-300 px-2">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-lg relative">
                <button onclick="closeWilayahModal()" class="absolute top-2 right-2 text-gray-400 hover:text-red-600 text-2xl">&times;</button>
                <h2 id="wilayahModalTitle" class="text-xl font-bold mb-4 text-[#0088cc]">Tambah Data Wilayah</h2>
                <form id="wilayahForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="wilayahFormMethod" value="POST">
                    <input type="hidden" name="id" id="wilayahId">
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Nama/Kelompok/Jenis</label>
                        <input type="text" name="nama" id="wilayahNama" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Jumlah Data</label>
                        <input type="number" name="jumlah" id="wilayahJumlah" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Laki-laki</label>
                        <input type="number" name="laki_laki" id="wilayahLaki" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Perempuan</label>
                        <input type="number" name="perempuan" id="wilayahPerempuan" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-[#0088cc] text-white px-4 py-2 rounded hover:bg-[#006fa1] font-semibold">Simpan</button>
                        <button type="button" onclick="closeWilayahModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded font-semibold">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@include('admin.partials.footer')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openWilayahModal(id = null, nama = '', jumlah = '', laki = '', perempuan = '') {
        const modal = document.getElementById('wilayahModal');
        modal.classList.remove('pointer-events-none');
        setTimeout(() => {
            modal.classList.add('!opacity-100');
            modal.classList.remove('opacity-0');
        }, 10);
        document.getElementById('wilayahForm').reset();
        if (id) {
            document.getElementById('wilayahModalTitle').innerText = 'Edit Data Wilayah';
            document.getElementById('wilayahForm').action = '/admin/wilayah/' + id;
            document.getElementById('wilayahFormMethod').value = 'PUT';
            document.getElementById('wilayahId').value = id;
            document.getElementById('wilayahNama').value = nama;
            document.getElementById('wilayahJumlah').value = jumlah;
            document.getElementById('wilayahLaki').value = laki;
            document.getElementById('wilayahPerempuan').value = perempuan;
        } else {
            document.getElementById('wilayahModalTitle').innerText = 'Tambah Data Wilayah';
            document.getElementById('wilayahForm').action = '/admin/wilayah';
            document.getElementById('wilayahFormMethod').value = 'POST';
            document.getElementById('wilayahId').value = '';
            document.getElementById('wilayahNama').value = '';
            document.getElementById('wilayahJumlah').value = '';
            document.getElementById('wilayahLaki').value = '';
            document.getElementById('wilayahPerempuan').value = '';
        }
    }
    function closeWilayahModal() {
        const modal = document.getElementById('wilayahModal');
        modal.classList.remove('!opacity-100');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('pointer-events-none');
        }, 300);
    }

    // SweetAlert for success messages
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif

    // SweetAlert2 confirm for all delete buttons
    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Yakin hapus data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
