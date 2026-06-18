@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Tugas Anda</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau dan kelola seluruh tugas departemen yang diberikan oleh manajer Anda.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('staff.tugas.index') }}" id="filter-form">
        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                 <!-- Status -->
                 <div class="w-full">
                     <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Status</label>
                     <select name="status" id="filter-status" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all cursor-pointer appearance-none">
                         <option value="">Semua Status</option>
                         <option value="Belum Dikerjakan" {{ request('status') === 'Belum Dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                         <option value="Menunggu Persetujuan" {{ request('status') === 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                         <option value="Revisi" {{ request('status') === 'Revisi' ? 'selected' : '' }}>Revisi</option>
                         <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                     </select>
                 </div>

                 <!-- Prioritas -->
                 <div class="w-full">
                     <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Prioritas</label>
                     <select name="prioritas" id="filter-prioritas" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all cursor-pointer appearance-none">
                         <option value="">Semua Prioritas</option>
                         <option value="Rendah" {{ request('prioritas') === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                         <option value="Sedang" {{ request('prioritas') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                         <option value="Tinggi" {{ request('prioritas') === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                     </select>
                 </div>

                 <!-- Kategori -->
                 <div class="w-full">
                     <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Kategori</label>
                     <select name="kategori" id="filter-kategori" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all cursor-pointer appearance-none">
                         <option value="">Semua Kategori</option>
                         <option value="Individu" {{ request('kategori') === 'Individu' ? 'selected' : '' }}>Individu</option>
                         <option value="Kelompok" {{ request('kategori') === 'Kelompok' ? 'selected' : '' }}>Departemen</option>
                     </select>
                 </div>

                 <!-- Action Buttons -->
                 <div class="flex gap-2 w-full justify-end">
                     <button type="submit" class="px-4 py-2 bg-[#3B28CC] text-white text-sm font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors flex items-center gap-2 cursor-pointer w-full justify-center">
                         <i class="fa-solid fa-filter text-xs"></i> Filter
                     </button>
                     @if(request()->anyFilled(['status', 'prioritas', 'kategori']))
                         <a href="{{ route('staff.tugas.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2 w-full justify-center">
                             <i class="fa-solid fa-xmark text-xs"></i> Reset
                         </a>
                     @endif
                 </div>
            </div>
        </div>
    </form>

    <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="p-4">Nama Tugas</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Prioritas</th>
                        <th class="p-4">Mulai</th>
                        <th class="p-4">Batas Waktu</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                    @forelse($tugas as $item)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="p-4 font-medium text-gray-900">{{ $item->nama_tugas }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-lg">
                                    {{ $item->kategoritugas === 'Kelompok' ? 'Departemen' : $item->kategoritugas }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($item->prioritas == 'Tinggi')
                                    <span class="px-2.5 py-0.5 text-xs font-medium bg-red-50 text-red-700 rounded-lg">{{ $item->prioritas }}</span>
                                @elseif($item->prioritas == 'Sedang')
                                    <span class="px-2.5 py-0.5 text-xs font-medium bg-amber-50 text-amber-700 rounded-lg">{{ $item->prioritas }}</span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-medium bg-green-50 text-green-700 rounded-lg">{{ $item->prioritas }}</span>
                                @endif
                            </td>
                            <td class="p-4 font-mono text-xs text-gray-600">
                                {{ \Carbon\Carbon::parse($item->tanggal_tugas)->format('d M Y, H:i') }}
                            </td>
                            <td class="p-4 font-mono text-xs text-gray-600">
                                @php $isOverdue = \Carbon\Carbon::parse($item->deadline_tugas)->isPast() && $item->status_tugas !== 'Selesai'; @endphp
                                <span class="{{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-600' }}">{{ \Carbon\Carbon::parse($item->deadline_tugas)->format('d M Y, H:i') }}</span>
                                @if($isOverdue)<br><span class="text-[9px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-md mt-0.5 inline-block">Terlewat</span>@endif
                            </td>
                            <td class="p-4">
                                @php
                                    $statusColor = match($item->status_tugas) {
                                        'Selesai'              => 'bg-green-50 text-green-700 border border-green-200',
                                        'Revisi'               => 'bg-rose-50 text-rose-700 border border-rose-200',
                                        'Menunggu Persetujuan' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                        default                => 'bg-gray-100 text-gray-700 border border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-md {{ $statusColor }}">
                                    {{ $item->status_tugas == 'Menunggu Persetujuan' ? 'Menunggu Review' : ($item->status_tugas ?? 'Baru') }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('staff.tugas.show', $item->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg shadow-sm transition-colors gap-1.5">
                                    <i class="fa-solid fa-eye text-[10px]"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto">
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
    </div>

    <div class="grid grid-cols-1 gap-4 md:hidden">
        @forelse($tugas as $item)
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 leading-snug">{{ $item->nama_tugas }}</h3>
                        @php $isOverdueMobile = \Carbon\Carbon::parse($item->deadline_tugas)->isPast() && $item->status_tugas !== 'Selesai'; @endphp
                        <p class="text-[10px] {{ $isOverdueMobile ? 'text-red-600 font-semibold' : 'text-gray-400' }} mt-1">Deadline: {{ \Carbon\Carbon::parse($item->deadline_tugas)->format('d M Y, H:i') }}
                            @if($isOverdueMobile)<span class="text-[9px] font-bold text-red-500 bg-red-50 px-1 py-0.5 rounded-md ml-1">Terlewat</span>@endif
                        </p>
                    </div>
                    @php
                        $statusColor = match($item->status_tugas) {
                            'Selesai'              => 'bg-green-50 text-green-700 border border-green-200',
                            'Revisi'               => 'bg-rose-50 text-rose-700 border border-rose-200',
                            'Menunggu Persetujuan' => 'bg-blue-50 text-blue-700 border border-blue-200',
                            default                => 'bg-gray-100 text-gray-700 border border-gray-200',
                        };
                    @endphp
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-md {{ $statusColor }}">
                        {{ $item->status_tugas == 'Menunggu Persetujuan' ? 'Menunggu Review' : ($item->status_tugas ?? 'Baru') }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs pt-3 border-t border-gray-50">
                    <div>
                        <span class="text-gray-400 block mb-0.5">Kategori:</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-gray-100 text-gray-700 font-semibold rounded-md text-[10px]">
                            {{ $item->kategoritugas === 'Kelompok' ? 'Departemen' : $item->kategoritugas }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Prioritas:</span>
                        @if($item->prioritas == 'Tinggi')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 font-semibold rounded-md text-[10px] bg-red-50 text-red-700">{{ $item->prioritas }}</span>
                        @elseif($item->prioritas == 'Sedang')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 font-semibold rounded-md text-[10px] bg-amber-50 text-amber-700">{{ $item->prioritas }}</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 font-semibold rounded-md text-[10px] bg-green-50 text-green-700">{{ $item->prioritas }}</span>
                        @endif
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-50 flex items-center justify-end">
                    <a href="{{ route('staff.tugas.show', $item->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg shadow-sm transition-colors gap-1.5 w-full">
                        <i class="fa-solid fa-eye text-[10px]"></i> Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-2xl border border-gray-100 shadow-sm text-center text-sm text-gray-400">
                <div class="flex flex-col items-center gap-3 text-gray-400">
                    <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto">
                        <i class="fa-solid fa-list-check text-2xl text-gray-300"></i>
                    </div>
                    <p class="font-semibold text-gray-500">Tidak ada data tugas</p>
                    <p class="text-xs text-gray-400">Coba ubah atau reset filter yang aktif.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>

<script>
    // Auto-submit form ketika dropdown filter berubah
    ['filter-status', 'filter-prioritas', 'filter-kategori'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        }
    });
</script>
@endsection
