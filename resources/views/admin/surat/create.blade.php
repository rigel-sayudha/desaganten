@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Pengelolaan Surat', 'url' => url('/admin/surat')],
    ['label' => 'Tambah Surat Baru', 'url' => '#'],
];
@endphp

@include('admin.partials.alpinejs')
@include('admin.partials.navbar')

<div id="adminLayout" x-data="{ sidebarMinimized: false }" class="flex min-h-screen bg-gray-50">
    @include('admin.partials.sidebar')
    
    <main id="adminMain" 
          class="flex-1 pt-24 pb-8 px-2 sm:px-4 md:px-8 transition-all duration-300"
          :class="{
              'ml-16': $store.sidebar && $store.sidebar.isOpen && sidebarMinimized,
              'ml-64': $store.sidebar && $store.sidebar.isOpen && !sidebarMinimized,
              'ml-0': !$store.sidebar || !$store.sidebar.isOpen
          }"
          x-init="
              $watch('$store.sidebar.isOpen', value => {
                  if (!value) sidebarMinimized = false;
              });
              document.addEventListener('sidebar-minimized', (e) => {
                  sidebarMinimized = e.detail.minimized;
              });
          "
    >
        <div class="max-w-4xl mx-auto w-full">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-[#0088cc] mb-2">Tambah Surat Keterangan Baru</h1>
                <p class="text-gray-600">Pilih jenis surat keterangan yang ingin dibuat dan isi formulir di bawah ini.</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Container -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-[#0088cc] to-blue-600 text-white p-6">
                    <h2 class="text-xl font-semibold">Formulir Surat Keterangan</h2>
                </div>

                <div class="p-6" x-data="suratForm()">
                    <!-- Pilihan Jenis Surat -->
                    <div class="mb-6">
                        <label for="jenis_surat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Surat Keterangan <span class="text-red-500">*</span>
                        </label>
                        <select x-model="selectedType" @change="handleTypeChange()" 
                                id="jenis_surat" name="jenis_surat" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent">
                            <option value="">-- Pilih Jenis Surat --</option>
                            <option value="domisili">Surat Keterangan Domisili</option>
                            <option value="belum_menikah">Surat Keterangan Belum Menikah</option>
                            <option value="tidak_mampu">Surat Keterangan Tidak Mampu</option>
                            <option value="ktp">Surat Keterangan KTP</option>
                            <option value="kk">Surat Keterangan KK</option>
                            <option value="skck">Surat Pengantar SKCK</option>
                            <option value="kematian">Surat Keterangan Kematian</option>
                            <option value="kelahiran">Surat Keterangan Kelahiran</option>
                        </select>
                    </div>

                    <!-- Form Fields Container -->
                    <form :action="formAction" method="POST" x-show="selectedType" x-transition>
                        @csrf
                        <input type="hidden" name="jenis_surat" :value="selectedType">

                        <div id="formFields">
                            <!-- Dynamic form fields will be loaded here -->
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex gap-4">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#0088cc] to-blue-600 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-all duration-200">
                                <i class="fas fa-save mr-2"></i>Simpan Surat
                            </button>
                            <a href="{{ route('admin.surat.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg shadow-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@include('admin.partials.footer')

@push('scripts')
<script>
function suratForm() {
    return {
        selectedType: '',
        formAction: '',
        
        handleTypeChange() {
            this.formAction = `/admin/surat`;
            this.loadFormFields();
        },
        
        loadFormFields() {
            const container = document.getElementById('formFields');
            
            // Clear existing fields
            container.innerHTML = '';
            
            // Common fields for all types
            const commonFields = this.createCommonFields();
            container.appendChild(commonFields);
            
            // Type-specific fields
            switch(this.selectedType) {
                case 'domisili':
                    container.appendChild(this.createDomisiliFields());
                    break;
                case 'belum_menikah':
                    container.appendChild(this.createBelumMenikahFields());
                    break;
                case 'tidak_mampu':
                    container.appendChild(this.createTidakMampuFields());
                    break;
                case 'ktp':
                case 'kk':
                case 'skck':
                case 'kematian':
                case 'kelahiran':
                    container.appendChild(this.createGeneralFields());
                    break;
            }
        },
        
        createCommonFields() {
            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-2 gap-6 mb-6';
            div.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                           placeholder="Masukkan nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" required maxlength="16"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                           placeholder="Masukkan NIK (16 digit)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tempat Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tempat_lahir" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                           placeholder="Masukkan tempat lahir">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_lahir" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_kelamin" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Agama <span class="text-red-500">*</span>
                    </label>
                    <select name="agama" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent">
                        <option value="">-- Pilih Agama --</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>
            `;
            return div;
        },
        
        createDomisiliFields() {
            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-2 gap-6 mb-6';
            div.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Perkawinan <span class="text-red-500">*</span>
                    </label>
                    <select name="status_perkawinan" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent">
                        <option value="">-- Pilih Status --</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pekerjaan" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                           placeholder="Masukkan pekerjaan">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" required rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                              placeholder="Masukkan alamat lengkap"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keperluan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" required rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                              placeholder="Masukkan keperluan surat domisili"></textarea>
                </div>
            `;
            return div;
        },
        
        createBelumMenikahFields() {
            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-2 gap-6 mb-6';
            div.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pekerjaan" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                           placeholder="Masukkan pekerjaan">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kewarganegaraan <span class="text-red-500">*</span>
                    </label>
                    <select name="kewarganegaraan" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent">
                        <option value="">-- Pilih Kewarganegaraan --</option>
                        <option value="WNI">WNI (Warga Negara Indonesia)</option>
                        <option value="WNA">WNA (Warga Negara Asing)</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" required rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                              placeholder="Masukkan alamat lengkap"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keperluan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" required rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                              placeholder="Masukkan keperluan surat belum menikah"></textarea>
                </div>
            `;
            return div;
        },
        
        createTidakMampuFields() {
            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-2 gap-6 mb-6';
            div.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pekerjaan" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                           placeholder="Masukkan pekerjaan">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Perkawinan <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent">
                        <option value="">-- Pilih Status --</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" required rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                              placeholder="Masukkan alamat lengkap"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keperluan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" required rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                              placeholder="Masukkan keperluan surat tidak mampu"></textarea>
                </div>
            `;
            return div;
        },
        
        createGeneralFields() {
            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-2 gap-6 mb-6';
            div.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pekerjaan" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                           placeholder="Masukkan pekerjaan">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Perkawinan <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent">
                        <option value="">-- Pilih Status --</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" required rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                              placeholder="Masukkan alamat lengkap"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keperluan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" required rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0088cc] focus:border-transparent"
                              placeholder="Masukkan keperluan surat"></textarea>
                </div>
            `;
            return div;
        }
    }
}
</script>
@endpush
@endsection
