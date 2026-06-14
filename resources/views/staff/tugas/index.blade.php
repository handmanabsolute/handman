@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Tugas Anda</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau dan kelola seluruh tugas departemen yang diberikan oleh manajer Anda.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="p-4">Nama Tugas</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Prioritas</th>
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
                                {{ \Carbon\Carbon::parse($item->deadline_tugas)->format('d M Y, H:i') }}
                            </td>
                            <td class="p-4">
                                @if($item->status_tugas == 'Selesai')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-green-50 text-green-700 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Selesai
                                    </span>
                                @elseif($item->status_tugas == 'Revisi')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-rose-50 text-rose-700 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span> Revisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Baru
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('staff.tugas.show', $item->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg shadow-sm transition-colors gap-1.5">
                                    <i class="fa-solid fa-eye text-[10px]"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-list-check text-2xl"></i>
                                    <p class="text-sm">Belum ada tugas yang ditugaskan ke departemen Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
