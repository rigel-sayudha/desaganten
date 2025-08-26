@extends('layouts.app')

@section('content')
@include('partials.navbar')

<!-- Flash message untuk sukses -->
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#0088cc'
        });
    });
</script>
@endif

<div class="max-w-4xl mx-auto mt-12 bg-white p-8 rounded-lg shadow-lg">
    @php
    $breadcrumbs = [
        ['label' => 'Beranda', 'url' => url('/')],
        ['label' => 'Surat', 'url' => url('/surat/form')],
        ['label' => 'Belum Menikah', 'url' => '#'],
    ];
    @endphp
    @include('partials.breadcrumbs', ['items' => $breadcrumbs])
    
    <h2 class="text-2xl font-bold mb-8 text-[#0088cc] text-center">Formulir Permohonan Surat Keterangan Belum Menikah</h2>
    
    <div x-data="{ 
        isSubmitting: false,
        submitForm() {
            this.isSubmitting = true;
            this.$refs.form.submit();
        },
        resetSubmission() {
            this.isSubmitting = false;
        }
    }" x-init="
        // Reset submission state if form errors exist
        if({{ $errors->any() ? 'true' : 'false' }}) {
            resetSubmission();
        }
    ">
    
    <form x-ref="form" action="{{ route('surat.belum-menikah.submit') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Data Pemohon -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Data Pemohon</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('nama') border-red-500 @enderror"
                           value="{{ old('nama') }}" placeholder="Masukkan nama lengkap">
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" name="nik" id="nik" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('nik') border-red-500 @enderror"
                           value="{{ old('nik') }}" placeholder="Masukkan NIK 16 digit">
                    @error('nik')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('tempat_lahir') border-red-500 @enderror"
                           value="{{ old('tempat_lahir') }}" placeholder="Contoh: Jakarta">
                    @error('tempat_lahir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('tanggal_lahir') border-red-500 @enderror"
                           value="{{ old('tanggal_lahir') }}">
                    @error('tanggal_lahir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('jenis_kelamin') border-red-500 @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="agama" class="block text-sm font-medium text-gray-700 mb-2">Agama</label>
                    <select name="agama" id="agama" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('agama') border-red-500 @enderror">
                        <option value="">Pilih Agama</option>
                        <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                    @error('agama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                    <input type="text" name="pekerjaan" id="pekerjaan" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('pekerjaan') border-red-500 @enderror"
                           value="{{ old('pekerjaan') }}" placeholder="Contoh: Karyawan Swasta">
                    @error('pekerjaan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('no_telepon') border-red-500 @enderror"
                           value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789">
                    @error('no_telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" rows="3" required 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('alamat') border-red-500 @enderror"
                          placeholder="Masukkan alamat lengkap tempat tinggal">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Data Orang Tua -->
        <div class="bg-green-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Data Orang Tua</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nama_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">Nama Orang Tua / Wali</label>
                    <input type="text" name="nama_orang_tua" id="nama_orang_tua" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('nama_orang_tua') border-red-500 @enderror"
                           value="{{ old('nama_orang_tua') }}" placeholder="Masukkan nama orang tua / wali">
                    @error('nama_orang_tua')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pekerjaan_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Orang Tua / Wali</label>
                    <input type="text" name="pekerjaan_orang_tua" id="pekerjaan_orang_tua" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('pekerjaan_orang_tua') border-red-500 @enderror"
                           value="{{ old('pekerjaan_orang_tua') }}" placeholder="Contoh: Petani, Wiraswasta">
                    @error('pekerjaan_orang_tua')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="alamat_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">Alamat Orang Tua / Wali</label>
                <textarea name="alamat_orang_tua" id="alamat_orang_tua" rows="3" required 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('alamat_orang_tua') border-red-500 @enderror"
                          placeholder="Masukkan alamat lengkap orang tua / wali">{{ old('alamat_orang_tua') }}</textarea>
                @error('alamat_orang_tua')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Keperluan -->
        <div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Keperluan</h3>
            <div>
                <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-2">
                    Untuk keperluan apa surat keterangan belum menikah ini? <span class="text-red-500">*</span>
                </label>
                <textarea name="keperluan" id="keperluan" rows="3" required 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0088cc] @error('keperluan') border-red-500 @enderror"
                          placeholder="Contoh: Untuk keperluan melamar pekerjaan di instansi pemerintah">{{ old('keperluan') }}</textarea>
                @error('keperluan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Pernyataan -->
        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
            <h4 class="font-semibold text-yellow-800 mb-2">Pernyataan:</h4>
            <p class="text-sm text-yellow-700 mb-3">
                Dengan ini saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan. 
                Apabila di kemudian hari terbukti data yang saya berikan tidak benar, maka saya bersedia menerima sanksi sesuai ketentuan yang berlaku.
            </p>
            <label class="flex items-center">
                <input type="checkbox" name="pernyataan" required class="mr-2">
                <span class="text-sm text-gray-700">Saya menyetujui pernyataan di atas</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ url('/surat/form') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                Kembali
            </a>
            <button 
                type="button"
                @click="submitForm()"
                :disabled="isSubmitting"
                :class="{
                    'opacity-50 cursor-not-allowed': isSubmitting,
                    'hover:bg-[#006fa1]': !isSubmitting
                }"
                class="px-6 py-2 bg-[#0088cc] text-white rounded-md transition"
            >
                <span x-show="!isSubmitting" class="flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i>Ajukan Permohonan
                </span>
                <span x-show="isSubmitting" class="flex items-center">
                    <svg class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>
    </form>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Alpine.js Script -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
