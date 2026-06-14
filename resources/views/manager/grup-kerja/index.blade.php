@extends('layouts.app')

@section('title', 'Grup Kerja')

@section('content')
<div class="space-y-6 pb-10">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Grup Kerja</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola grup kerja staff di departemen Anda.</p>
        </div>
        <a href="{{ route('staff-divisi.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#3B28CC] text-white text-sm font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors">
            <i class="fa-solid fa-plus text-xs"></i> Buat Grup Baru
        </a>
    </div>

    
    @if(session('success'))
        <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-100 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-600 text-base shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    
    @if($grups->isEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl p-14 text-center shadow-sm">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-people-group text-2xl text-[#3B28CC]/40"></i>
                </div>
                <p class="font-semibold text-gray-500">Belum ada grup kerja</p>
                <p class="text-xs text-gray-400 max-w-xs">Pilih beberapa staff dari halaman Staff Divisi, lalu buat grup kerja dari pilihan tersebut.</p>
                <a href="{{ route('staff-divisi.index') }}"
                   class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-[#3B28CC] text-white text-xs font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors">
                    <i class="fa-solid fa-users text-xs"></i> Ke Halaman Staff Divisi
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($grups as $grup)
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden">

                
                <div class="h-1.5 bg-gradient-to-r from-[#3B28CC] to-indigo-400"></div>

                <div class="p-5 flex flex-col gap-4 flex-1">
                    
                    <div class="space-y-1">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-base font-bold text-gray-900 leading-snug">{{ $grup->nama_grup }}</h3>
                            <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-[#3B28CC] text-[10px] font-bold rounded-md">
                                <i class="fa-solid fa-people-group text-[9px]"></i>
                                {{ $grup->anggota->count() }} anggota
                            </span>
                        </div>
                        @if($grup->deskripsi)
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">{{ $grup->deskripsi }}</p>
                        @else
                            <p class="text-xs text-gray-300 italic">Tidak ada deskripsi.</p>
                        @endif
                    </div>

                    
                    @if($grup->anggota->count() > 0)
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2">
                            @foreach($grup->anggota->take(5) as $anggota)
                                @if($anggota->foto_profil)
                                    <img src="{{ asset('storage/' . $anggota->foto_profil) }}"
                                         title="{{ $anggota->nama_lengkap }}"
                                         class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama_lengkap) }}&background=3B28CC&color=fff&size=64"
                                         title="{{ $anggota->nama_lengkap }}"
                                         class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm">
                                @endif
                            @endforeach
                            @if($grup->anggota->count() > 5)
                                <div class="w-8 h-8 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-gray-500 shadow-sm">
                                    +{{ $grup->anggota->count() - 5 }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400">
                        <span>Dibuat {{ \Carbon\Carbon::parse($grup->created_at)->diffForHumans() }}</span>
                        <span>oleh <span class="font-semibold text-gray-600">{{ $grup->creator->nama_lengkap ?? '-' }}</span></span>
                    </div>

                    
                    <div class="flex items-center gap-2">
                        <a href="{{ route('grup-kerja.show', $grup->id) }}"
                           class="flex-1 flex items-center justify-center gap-1.5 py-2 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                            <i class="fa-solid fa-eye text-xs"></i> Lihat Detail
                        </a>
                        <button type="button" onclick="openModal('dissolve-grup-{{ $grup->id }}')"
                                class="flex items-center justify-center w-9 h-9 border border-rose-100 text-rose-500 rounded-xl hover:bg-rose-50 transition-colors cursor-pointer"
                                title="Bubarkan Grup">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                        <x-confirm-modal 
                            id="dissolve-grup-{{ $grup->id }}" 
                            title="Bubarkan Grup" 
                            message="Apakah Anda yakin ingin membubarkan grup '{{ addslashes($grup->nama_grup) }}'? Tindakan ini tidak dapat dibatalkan." 
                            action="{{ route('grup-kerja.destroy', $grup->id) }}" 
                            method="DELETE" 
                            type="danger" 
                        />
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-xs text-gray-400 text-right">
            Total <span class="font-semibold text-gray-600">{{ $grups->count() }}</span> grup kerja
        </div>
    @endif

</div>
@endsection
