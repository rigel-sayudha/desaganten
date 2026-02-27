@extends('layouts.app')

@section('title', 'Test Notifikasi')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Test Sistem Notifikasi</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Simulasi Notifikasi Surat</h2>
        <p class="text-gray-600 mb-6">Gunakan tombol-tombol di bawah ini untuk menguji sistem notifikasi surat keterangan.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Test untuk surat domisili -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Surat Domisili</h3>
                <div class="space-y-2">
                    <button onclick="testNotification('domisili', 'diverifikasi')" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Diverifikasi
                    </button>
                    <button onclick="testNotification('domisili', 'diproses')" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Diproses
                    </button>
                    <button onclick="testNotification('domisili', 'ditolak')" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Ditolak
                    </button>
                </div>
            </div>

            <!-- Test untuk surat KTP -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Surat KTP</h3>
                <div class="space-y-2">
                    <button onclick="testNotification('ktp', 'diverifikasi')" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Diverifikasi
                    </button>
                    <button onclick="testNotification('ktp', 'diproses')" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Diproses
                    </button>
                    <button onclick="testNotification('ktp', 'ditolak')" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Ditolak
                    </button>
                </div>
            </div>

            <!-- Test untuk surat Belum Menikah -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Surat Belum Menikah</h3>
                <div class="space-y-2">
                    <button onclick="testNotification('belum_menikah', 'diverifikasi')" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Diverifikasi
                    </button>
                    <button onclick="testNotification('belum_menikah', 'diproses')" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Diproses
                    </button>
                    <button onclick="testNotification('belum_menikah', 'ditolak')" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Ditolak
                    </button>
                </div>
            </div>

            <!-- Test untuk surat SKCK -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Surat SKCK</h3>
                <div class="space-y-2">
                    <button onclick="testNotification('skck', 'diverifikasi')" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Diverifikasi
                    </button>
                    <button onclick="testNotification('skck', 'diproses')" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Diproses
                    </button>
                    <button onclick="testNotification('skck', 'ditolak')" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors">
                        Test: Surat Ditolak
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
            <h3 class="font-semibold text-blue-800 mb-2">Instruksi Testing:</h3>
            <ol class="list-decimal list-inside text-blue-700 space-y-1">
                <li>Pastikan sudah ada data surat dengan user_id yang valid</li>
                <li>Klik salah satu tombol test untuk mensimulasikan perubahan status</li>
                <li>Lihat notifikasi yang muncul di bell icon di navbar user</li>
                <li>Login sebagai user untuk melihat notifikasi di frontend</li>
                <li>Test dengan status yang berbeda untuk melihat variasi notifikasi</li>
            </ol>
        </div>
    </div>
</div>

<script>
async function testNotification(jenisSurat, status) {
    try {
        // Tampilkan loading
        Swal.fire({
            title: 'Mengirim Notifikasi...',
            text: `Test notifikasi untuk ${jenisSurat} - ${status}`,
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        const response = await fetch('/admin/test-notification', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                jenis_surat: jenisSurat,
                status: status
            })
        });

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: result.message,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            throw new Error(result.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'Terjadi kesalahan saat mengirim notifikasi'
        });
    }
}
</script>
@endsection
