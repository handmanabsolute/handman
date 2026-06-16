@extends('layouts.app')

@section('title', 'Detail Grup - {{ $grup->nama_grup }}')

@section('content')
<div class="space-y-6 pb-10">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Grup Kerja</h1>
            <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap dan daftar anggota grup.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('grup-kerja.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
            </a>
            <button type="button" onclick="openModal('dissolve-grup-{{ $grup->id }}')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-rose-50 text-rose-600 border border-rose-100 text-sm font-semibold rounded-xl hover:bg-rose-100 transition-colors cursor-pointer">
                <i class="fa-solid fa-trash-can text-xs"></i> Bubarkan Grup
            </button>
            <x-confirm-modal 
                id="dissolve-grup-{{ $grup->id }}" 
                title="Bubarkan Grup" 
                message="Apakah Anda yakin ingin membubarkan grup '{{ addslashes($grup->nama_grup) }}'?" 
                action="{{ route('grup-kerja.destroy', $grup->id) }}" 
                method="DELETE" 
                type="danger" 
            />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-[#3B28CC] to-indigo-400"></div>
                <div class="p-6 space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-people-group text-[#3B28CC] text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 leading-tight">{{ $grup->nama_grup }}</h2>
                            <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded-md">
                                {{ $grup->anggota->count() }} Anggota
                            </span>
                        </div>
                    </div>

                    @if($grup->deskripsi)
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Deskripsi</p>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $grup->deskripsi }}</p>
                    </div>
                    @endif

                    <div class="space-y-3 pt-2 border-t border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-building text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium">Departemen</p>
                                <p class="text-sm font-semibold text-gray-700">{{ $grup->departemen->nama_departemen ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-user-tie text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium">Dibuat Oleh</p>
                                <p class="text-sm font-semibold text-gray-700">{{ $grup->creator->nama_lengkap ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium">Tanggal Dibuat</p>
                                <p class="text-sm font-semibold text-gray-700">
                                    {{ \Carbon\Carbon::parse($grup->created_at)->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-users text-[#3B28CC]"></i>
                        Daftar Anggota
                        <span class="ml-1 px-2 py-0.5 bg-indigo-50 text-[#3B28CC] text-xs font-bold rounded-md">
                            {{ $grup->anggota->count() }}
                        </span>
                    </h3>
                </div>

                @if($grup->anggota->isEmpty())
                    <div class="p-10 text-center text-gray-400">
                        <i class="fa-solid fa-user-slash text-3xl text-gray-200 mb-3 block"></i>
                        <p class="text-sm font-medium">Tidak ada anggota dalam grup ini.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($grup->anggota as $anggota)
                        <a href="{{ route('staff-divisi.show', $anggota->id) }}"
                           class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/70 transition-colors group">
                            
                            @if($anggota->foto_profil)
                                <img src="{{ asset('storage/' . $anggota->foto_profil) }}"
                                     class="w-11 h-11 rounded-full object-cover border-2 border-indigo-50 shrink-0">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama_lengkap) }}&background=3B28CC&color=fff&size=128"
                                     class="w-11 h-11 rounded-full object-cover border-2 border-indigo-50 shrink-0">
                            @endif

                            
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate group-hover:text-[#3B28CC] transition-colors">
                                    {{ $anggota->nama_lengkap }}
                                </p>
                                <p class="text-xs text-gray-400 truncate">{{ $anggota->email }}</p>
                            </div>

                            
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded-md
                                    {{ $anggota->is_active ? 'bg-green-50 text-green-700' : 'bg-rose-50 text-rose-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $anggota->is_active ? 'bg-green-500' : 'bg-rose-500' }}"></span>
                                    {{ $anggota->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-gray-100 text-gray-600 rounded-md">
                                    {{ $anggota->status_pegawai }}
                                </span>
                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 group-hover:text-[#3B28CC] transition-colors"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
