@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Admin', 'url' => url('/admin/dashboard')],
    ['label' => 'Kelola Surat', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
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
              // Listen for minimize state changes from sidebar
              document.addEventListener('sidebar-minimized', (e) => {
                  sidebarMinimized = e.detail.minimized;
              });
          "
    >
        <div class="max-w-7xl mx-auto w-full">
            <h1 class="text-2xl md:text-3xl font-bold text-[#0088cc] mb-8 text-center md:text-left">Kelola Surat</h1>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                {{-- Konten kelola surat, misal tabel surat, filter, aksi, dsb --}}
            </div>
        </div>
    </main>
</div>
@include('admin.partials.footer')
@endsection
