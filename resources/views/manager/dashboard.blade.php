@extends('layouts.app')

@section('title', 'Dashboard Manager')

@section('content')
<div class="space-y-6 pb-10">

    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Utama</h1>
            <p class="text-sm text-gray-500 mt-0.5">Selamat datang kembali, <span class="font-semibold text-gray-700">{{ Auth::user()->nama_lengkap }}</span>. Berikut ringkasan departemen <span class="font-bold text-[#3B28CC]">{{ Auth::user()->departemen->nama_departemen ?? 'Umum' }}</span> hari ini.</p>
        </div>
        <span class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 shrink-0">

        
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Staff</span>
                <h3 class="text-2xl font-bold text-gray-800">{{ $staffCount }}</h3>
                <p class="text-[10px] text-gray-400">Pegawai di divisi Anda</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-[#3B28CC]">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tugas Selesai</span>
                <h3 class="text-2xl font-bold text-green-600">{{ $tugasSelesai }}</h3>
                <p class="text-[10px] text-gray-400">dari {{ $totalTugas }} total tugas</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                <i class="fa-solid fa-circle-check text-xl"></i>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tugas Berjalan</span>
                <h3 class="text-2xl font-bold text-amber-600">{{ $tugasBerjalan }}</h3>
                <p class="text-[10px] text-gray-400">
                    <span class="text-blue-500 font-semibold">{{ $tugasPending }} menunggu review</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                <i class="fa-solid fa-clock text-xl"></i>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Efisiensi Kerja</span>
                <h3 class="text-2xl font-bold
                    {{ $efisiensi >= 75 ? 'text-green-600' : ($efisiensi >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                    {{ $efisiensi }}%
                </h3>
                <p class="text-[10px] text-gray-400">tugas diselesaikan</p>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 shrink-0">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600 shrink-0">
                <i class="fa-solid fa-people-group text-base"></i>
            </div>
            <div>
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">Grup Kerja</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalGrup }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 shrink-0">
                <i class="fa-solid fa-diagram-project text-base"></i>
            </div>
            <div>
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">Tugas Departemen</p>
                <p class="text-xl font-bold text-gray-800">{{ $tugasKelompok }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 shrink-0">
                <i class="fa-solid fa-arrow-rotate-left text-base"></i>
            </div>
            <div>
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">Tugas Revisi</p>
                <p class="text-xl font-bold text-gray-800">{{ $tugasRevisi }}</p>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Tugas Terbaru</h2>
                <p class="text-xs text-gray-400 mt-0.5">5 tugas yang paling baru dibuat.</p>
            </div>
            <a href="{{ route('tugas.index') }}" class="text-xs font-semibold text-[#3B28CC] hover:underline">Lihat semua →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($tugas as $t)
            @php
                $statusConfig = match($t->status_tugas) {
                    'Selesai'              => ['bg-green-50 text-green-600', 'fa-circle-check'],
                    'Menunggu Persetujuan' => ['bg-blue-50 text-blue-600', 'fa-hourglass-half'],
                    'Revisi'               => ['bg-rose-50 text-rose-600', 'fa-arrow-rotate-left'],
                    default                => ['bg-gray-100 text-gray-500', 'fa-clock'],
                };
                $prioritasColor = match($t->prioritas) {
                    'Tinggi' => 'bg-red-50 text-red-600',
                    'Sedang' => 'bg-orange-50 text-orange-600',
                    default  => 'bg-green-50 text-green-600',
                };
            @endphp
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50/60 transition-colors">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('tugas.show', $t->id) }}" class="text-sm font-semibold text-gray-800 truncate hover:text-[#3B28CC] block">
                        {{ $t->nama_tugas }}
                    </a>
                    <p class="text-[10px] text-gray-400 mt-0.5">
                        Kategori: {{ $t->kategoritugas === 'Kelompok' ? 'Departemen' : $t->kategoritugas }}
                        &nbsp;·&nbsp;Deadline: {{ \Carbon\Carbon::parse($t->deadline_tugas)->format('d M Y, H:i') }}
                    </p>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-md {{ $prioritasColor }}">{{ $t->prioritas }}</span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[9px] font-bold rounded-md {{ $statusConfig[0] }}">
                        <i class="fa-solid {{ $statusConfig[1] }} text-[8px]"></i>
                        {{ $t->status_tugas ?? 'Belum Dikerjakan' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-xs text-gray-400">Belum ada tugas.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
