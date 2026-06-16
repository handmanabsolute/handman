@extends('layouts.app')

@section('title', 'Monitor Tugas - Admin')

@section('content')
<div class="space-y-6 pb-10">


    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Monitor Tugas</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau seluruh tugas di semua departemen secara real-time.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.tugas.exportPdf', request()->query()) }}" target="_blank" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
            </a>
        </div>
    </div>


    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 shrink-0">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Tugas</span>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalTugas }}</h3>
            </div>
            <div class="w-11 h-11 bg-indigo-50 rounded-xl flex items-center justify-center text-[#3B28CC]">
                <i class="fa-solid fa-list-check text-lg"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Selesai</span>
                <h3 class="text-2xl font-bold text-green-600">{{ $tugasSelesai }}</h3>
            </div>
            <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                <i class="fa-solid fa-circle-check text-lg"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Berjalan</span>
                <h3 class="text-2xl font-bold text-amber-600">{{ $tugasBerjalan }}</h3>
            </div>
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                <i class="fa-solid fa-clock text-lg"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Menunggu Review</span>
                <h3 class="text-2xl font-bold text-blue-600">{{ $tugasMenunggu }}</h3>
            </div>
            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                <i class="fa-solid fa-hourglass-half text-lg"></i>
            </div>
        </div>
    </div>


    <form method="GET" action="{{ route('admin.tugas.index') }}" id="filter-form">
        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3 items-end">

                <div class="w-full sm:flex-1">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Departemen</label>
                    <select name="departemen_id" id="filter-departemen" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all appearance-none cursor-pointer">
                        <option value="">Semua Departemen</option>
                        @foreach($departemens as $dep)
                            <option value="{{ $dep->id }}" {{ request('departemen_id') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->nama_departemen }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 w-full sm:w-auto shrink-0 justify-end">
                    <button type="submit" class="px-4 py-2 bg-[#3B28CC] text-white text-sm font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors flex items-center gap-2 cursor-pointer w-full sm:w-auto justify-center">
                        <i class="fa-solid fa-filter text-xs"></i> Filter
                    </button>
                    @if(request()->filled('departemen_id'))
                        <a href="{{ route('admin.tugas.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2 w-full sm:w-auto justify-center">
                            <i class="fa-solid fa-xmark text-xs"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>


    <div class="hidden md:block bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-600 uppercase tracking-wider">
                        <th class="p-4">Nama Tugas</th>
                        <th class="p-4">Departemen</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Prioritas</th>
                        <th class="p-4">Deadline</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($tugas as $t)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4">
                            <p class="font-semibold text-gray-900 max-w-55 truncate" title="{{ $t->nama_tugas }}">{{ $t->nama_tugas }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Dibuat: {{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</p>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-lg">
                                <i class="fa-solid fa-building text-[9px]"></i>
                                {{ $t->departemen->nama_departemen ?? '-' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $t->kategoritugas === 'Kelompok' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $t->kategoritugas === 'Kelompok' ? 'Departemen' : $t->kategoritugas }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg
                                {{ $t->prioritas === 'Tinggi' ? 'bg-red-50 text-red-600' :
                                   ($t->prioritas === 'Sedang' ? 'bg-orange-50 text-orange-600' : 'bg-green-50 text-green-600') }}">
                                {{ $t->prioritas }}
                            </span>
                        </td>
                        <td class="p-4">
                            @php
                                $deadline = \Carbon\Carbon::parse($t->deadline_tugas);
                                $isOverdue = $deadline->isPast() && $t->status_tugas !== 'Selesai';
                            @endphp
                            <p class="text-xs {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                {{ $deadline->format('d M Y, H:i') }}
                            </p>
                            @if($isOverdue)
                                <span class="text-[9px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-md">Melewati Deadline</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @php
                                $statusConfig = match($t->status_tugas) {
                                    'Selesai'              => ['bg-green-50 text-green-600', 'fa-circle-check'],
                                    'Menunggu Persetujuan' => ['bg-blue-50 text-blue-600', 'fa-hourglass-half'],
                                    'Revisi'               => ['bg-rose-50 text-rose-600', 'fa-arrow-rotate-left'],
                                    default                => ['bg-gray-100 text-gray-600', 'fa-clock'],
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg {{ $statusConfig[0] }}">
                                <i class="fa-solid {{ $statusConfig[1] }} text-[9px]"></i>
                                {{ $t->status_tugas ?? 'Belum Dikerjakan' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-list-check text-2xl text-gray-300"></i>
                                </div>
                                <p class="font-semibold text-gray-500">Tidak ada data tugas</p>
                                <p class="text-xs text-gray-400">Coba ubah atau reset filter yang aktif.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tugas->count() > 0)
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <p class="text-xs text-gray-500">
                Menampilkan <span class="font-semibold text-gray-700">{{ $tugas->count() }}</span> tugas
                @if(request()->hasAny(['search', 'departemen_id', 'status', 'prioritas', 'kategori']))
                    dari hasil filter
                @endif
            </p>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($tugas as $t)
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 leading-snug">{{ $t->nama_tugas }}</h3>
                        <p class="text-[10px] text-gray-400 mt-1">Dibuat: {{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</p>
                    </div>
                    @php
                        $statusConfig = match($t->status_tugas) {
                            'Selesai'              => ['bg-green-50 text-green-600', 'fa-circle-check'],
                            'Menunggu Persetujuan' => ['bg-blue-50 text-blue-600', 'fa-hourglass-half'],
                            'Revisi'               => ['bg-rose-50 text-rose-600', 'fa-arrow-rotate-left'],
                            default                => ['bg-gray-100 text-gray-600', 'fa-clock'],
                        };
                    @endphp
                    <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg {{ $statusConfig[0] }}">
                        <i class="fa-solid {{ $statusConfig[1] }} text-[9px]"></i>
                        {{ $t->status_tugas ?? 'Belum Dikerjakan' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs pt-3 border-t border-gray-50">
                    <div>
                        <span class="text-gray-400 block mb-0.5">Departemen:</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-700 font-semibold rounded-md text-[10px]">
                            {{ $t->departemen->nama_departemen ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Prioritas:</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold rounded-md text-[10px]
                            {{ $t->prioritas === 'Tinggi' ? 'bg-red-50 text-red-600' :
                               ($t->prioritas === 'Sedang' ? 'bg-orange-50 text-orange-600' : 'bg-green-50 text-green-600') }}">
                            {{ $t->prioritas }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs pt-2">
                    <div>
                        <span class="text-gray-400 block mb-0.5">Kategori:</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-semibold rounded-md text-[10px] {{ $t->kategoritugas === 'Kelompok' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $t->kategoritugas === 'Kelompok' ? 'Departemen' : $t->kategoritugas }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Deadline:</span>
                        @php
                            $deadline = \Carbon\Carbon::parse($t->deadline_tugas);
                            $isOverdue = $deadline->isPast() && $t->status_tugas !== 'Selesai';
                        @endphp
                        <p class="text-[11px] font-medium {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                            {{ $deadline->format('d M Y, H:i') }}
                        </p>
                        @if($isOverdue)
                            <span class="text-[9px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-md mt-0.5 inline-block">Melewati Deadline</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm text-center text-sm text-gray-400">
                Tidak ada data tugas.
            </div>
        @endforelse
        @if($tugas->count() > 0)
        <div class="bg-white px-5 py-3.5 border border-gray-100 rounded-2xl shadow-sm text-center">
            <p class="text-xs text-gray-500 font-medium">
                Menampilkan <span class="font-bold text-gray-700">{{ $tugas->count() }}</span> tugas
            </p>
        </div>
        @endif
    </div>
</div>

<script>
    ['filter-departemen', 'filter-status', 'filter-prioritas', 'filter-kategori'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        }
    });

    let searchTimer;
    const searchInput = document.getElementById('filter-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                document.getElementById('filter-form').submit();
            }, 500);
        });
    }
</script>
@endsection
