@extends('layouts.app')

@section('title', 'Laporan Masuk')

@section('content')
<div class="space-y-6 pb-10">

    
    <div class="flex items-center justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Masuk</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau dan berikan respon tanggapan terhadap laporan dari Staff dan Manager.</p>
        </div>
    </div>

    
    @if(session('success'))
        <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-100 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-600 text-base shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 text-sm text-rose-800 bg-rose-50 border border-rose-100 rounded-xl space-y-1">
            <div class="flex items-center gap-2 font-bold">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-base"></i> Terjadi Kesalahan:
            </div>
            <ul class="list-disc pl-5 text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    
    <div class="border-b border-gray-200">
        <nav class="flex w-full" aria-label="Tabs">
            <a href="{{ route('admin.laporan.index') }}"
               class="flex-1 text-center py-4 border-b-2 text-sm flex items-center justify-center gap-2 transition-all cursor-pointer
                {{ request('status') !== 'Selesai'
                    ? 'border-[#3B28CC] text-[#3B28CC] font-bold'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-semibold' }}">
                <i class="fa-solid fa-inbox text-sm"></i>
                Belum Dibalas
                <span class="ml-1 py-0.5 px-2.5 rounded-full text-xs font-bold {{ request('status') !== 'Selesai' ? 'bg-indigo-50 text-[#3B28CC]' : 'bg-gray-100 text-gray-600' }}">
                    {{ \App\Models\Laporan::whereIn('status', ['Menunggu', 'Dibalas'])->count() }}
                </span>
            </a>
            <a href="{{ route('admin.laporan.index', ['status' => 'Selesai']) }}"
               class="flex-1 text-center py-4 border-b-2 text-sm flex items-center justify-center gap-2 transition-all cursor-pointer
                {{ request('status') === 'Selesai'
                    ? 'border-[#3B28CC] text-[#3B28CC] font-bold'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-semibold' }}">
                <i class="fa-solid fa-circle-check text-sm"></i>
                Laporan Selesai
                <span class="ml-1 py-0.5 px-2.5 rounded-full text-xs font-bold {{ request('status') === 'Selesai' ? 'bg-indigo-50 text-[#3B28CC]' : 'bg-gray-100 text-gray-600' }}">
                    {{ \App\Models\Laporan::where('status', 'Selesai')->count() }}
                </span>
            </a>
        </nav>
    </div>

    
    @if($laporans->isEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl p-14 text-center shadow-sm">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center text-[#3B28CC]">
                    <i class="fa-solid fa-inbox text-2xl"></i>
                </div>
                <p class="font-semibold text-gray-500">Tidak ada laporan masuk</p>
                <p class="text-xs text-gray-400 max-w-xs">Saat ini tidak ada laporan dengan status terpilih yang masuk ke sistem.</p>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($laporans as $laporan)
            <div class="bg-white border border-gray-100 rounded-2xl shadow-xs overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        @if($laporan->user->foto_profil)
                            <img src="{{ asset('storage/' . $laporan->user->foto_profil) }}"
                                 class="w-10 h-10 rounded-full object-cover border border-indigo-50 shrink-0">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($laporan->user->nama_lengkap) }}&background=3B28CC&color=fff&size=64"
                                 class="w-10 h-10 rounded-full object-cover border border-indigo-50 shrink-0">
                        @endif
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $laporan->user->nama_lengkap }}</p>
                                <span class="px-2 py-0.5 text-[9px] font-bold rounded-md uppercase tracking-wider
                                    {{ $laporan->user->nama_role === 'manager' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $laporan->user->nama_role }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $laporan->user->departemen->nama_departemen ?? '-' }} &bull; {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y, H:i') }}</p>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-1 font-medium">{{ $laporan->isi }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 self-end sm:self-center shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-lg border
                            {{ $laporan->status === 'Menunggu'
                                ? 'bg-amber-50 text-amber-700 border-amber-100'
                                : ($laporan->status === 'Dibalas'
                                    ? 'bg-blue-50 text-blue-700 border-blue-100'
                                    : 'bg-green-50 text-green-700 border-green-100') }}">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ $laporan->status === 'Menunggu'
                                    ? 'bg-amber-500'
                                    : ($laporan->status === 'Dibalas'
                                        ? 'bg-blue-500'
                                        : 'bg-green-500') }}"></span>
                            {{ $laporan->status === 'Menunggu' ? 'Belum Dibalas' : $laporan->status }}
                        </span>
                        
                        <a href="{{ route('laporan.show', $laporan->id) }}"
                           class="bg-white border border-gray-200 text-gray-700 hover:text-[#3B28CC] hover:bg-indigo-50/50 px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                            Lihat Detail
                        </a>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
