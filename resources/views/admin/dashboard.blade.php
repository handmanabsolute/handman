@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Utama</h1>
            <p class="text-sm text-gray-500 mt-0.5">Selamat datang kembali, Admin. Berikut ringkasan sistem hari ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 shrink-0">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Pegawai</span>
                <h3 class="text-2xl font-bold text-gray-800">124</h3>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-[#3B28CC]">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tugas Selesai</span>
                <h3 class="text-2xl font-bold text-gray-800">852</h3>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                <i class="fa-solid fa-circle-check text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tugas Berjalan</span>
                <h3 class="text-2xl font-bold text-gray-800">42</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                <i class="fa-solid fa-clock text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Efisiensi Kerja</span>
                <h3 class="text-2xl font-bold text-gray-800">94.2%</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>
        </div>
    </div>

</div>
@endsection
