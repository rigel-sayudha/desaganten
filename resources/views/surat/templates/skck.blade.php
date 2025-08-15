@extends('layouts.app')
@section('content')
@php
$breadcrumbs = [
    ['label' => 'Beranda', 'url' => url('/')],
    ['label' => 'Surat', 'url' => url('/surat/form')],
    ['label' => 'SKCK', 'url' => '#'],
];
@endphp
@include('partials.breadcrumbs', ['items' => $breadcrumbs])
<div class="max-w-xl mx-auto mt-12 bg-white p-8 rounded-lg shadow-lg text-center">
    <h2 class="text-2xl font-bold mb-4 text-yellow-600">Formulir Surat Pengantar SKCK</h2>
    <p class="text-gray-600">Halaman ini masih dalam pengembangan. Silakan kembali nanti.</p>
</div>
@endsection
