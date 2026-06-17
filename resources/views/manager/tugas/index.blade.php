@extends('layouts.app')

@section('title', 'Kelola Tugas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Tugas</h1>
            <p class="text-sm text-gray-500">Manajemen daftar tugas, kategori, dan batas waktu pengerjaan.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tugas.exportPdf', request()->query()) }}" target="_blank" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
            </a>
            <a href="{{ route('tugas.create') }}" class="bg-[#3B28CC] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#2c1fa3] transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Tugas
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('tugas.index') }}" id="filter-form">
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

                <div class="w-full">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Prioritas</label>
                    <select name="prioritas" id="filter-prioritas" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all cursor-pointer appearance-none">
                        <option value="">Semua Prioritas</option>
                        <option value="Rendah" {{ request('prioritas') === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="Sedang" {{ request('prioritas') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Tinggi" {{ request('prioritas') === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                    </select>
                </div>

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
                        <a href="{{ route('tugas.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2 w-full justify-center">
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
                        <th class="p-4">Mulai</th>
                        <th class="p-4">Deadline</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($tugas as $t)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="p-4 font-medium text-gray-900">{{ $t->nama_tugas }}</td>
                        <td class="p-4">{{ $t->departemen->nama_departemen ?? '-' }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $t->kategoritugas === 'Kelompok' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $t->kategoritugas === 'Kelompok' ? 'Departemen' : $t->kategoritugas }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $t->prioritas === 'Tinggi' ? 'bg-red-50 text-red-600' : ($t->prioritas === 'Sedang' ? 'bg-orange-50 text-orange-600' : 'bg-green-50 text-green-600') }}">
                                {{ $t->prioritas }}
                            </span>
                        </td>
                        <td class="p-4 text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($t->tanggal_tugas)->format('d M Y, H:i') }}</td>
                        <td class="p-4 text-xs text-gray-500">{{ \Carbon\Carbon::parse($t->deadline_tugas)->format('d M Y, H:i') }}</td>
                        <td class="p-4">
                            @php
                                $statusColor = match($t->status_tugas) {
                                    'Selesai'              => 'bg-green-50 text-green-600 border border-green-200',
                                    'Menunggu Persetujuan' => 'bg-blue-50 text-blue-600 border border-blue-200',
                                    'Revisi'               => 'bg-rose-50 text-rose-600 border border-rose-200',
                                    default                => 'bg-gray-100 text-gray-600 border border-gray-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-md {{ $statusColor }}">
                                {{ $t->status_tugas ?? 'Belum Dikerjakan' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('tugas.show', $t->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Detail">
                                    <i class="fa-solid fa-eye text-base"></i>
                                </a>
                                <a href="{{ route('tugas.edit', $t->id) }}" class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                </a>
                                <button type="button" onclick="openModal('delete-tugas-{{ $t->id }}')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all disabled:bg-gray-100 disabled:text-gray-300 cursor-pointer" title="Hapus">
                                    <i class="fa-solid fa-trash-can text-base"></i>
                                </button>
                                <x-confirm-modal
                                    id="delete-tugas-{{ $t->id }}"
                                    title="Hapus Tugas"
                                    message="Apakah Anda yakin ingin menghapus tugas '{{ addslashes($t->nama_tugas) }}'? Tindakan ini tidak dapat dibatalkan."
                                    action="{{ route('tugas.destroy', $t->id) }}"
                                    method="DELETE"
                                    type="danger"
                                />
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-12 text-center">
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
        @forelse($tugas as $t)
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 leading-snug">{{ $t->nama_tugas }}</h3>
                        <p class="text-[10px] text-gray-400 mt-1">Dibuat: {{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</p>
                    </div>
                    @php
                        $statusColor = match($t->status_tugas) {
                            'Selesai'              => 'bg-green-50 text-green-600 border border-green-200',
                            'Menunggu Persetujuan' => 'bg-blue-50 text-blue-600 border border-blue-200',
                            'Revisi'               => 'bg-rose-50 text-rose-600 border border-rose-200',
                            default                => 'bg-gray-100 text-gray-600 border border-gray-200',
                        };
                    @endphp
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded-md {{ $statusColor }}">
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

                <div class="pt-3 border-t border-gray-50 flex items-center justify-end gap-2">
                    <a href="{{ route('tugas.show', $t->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Detail">
                        <i class="fa-solid fa-eye text-base"></i>
                    </a>
                    <a href="{{ route('tugas.edit', $t->id) }}" class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                        <i class="fa-solid fa-pen-to-square text-base"></i>
                    </a>
                    <button type="button" onclick="openModal('delete-tugas-{{ $t->id }}')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all cursor-pointer" title="Hapus">
                        <i class="fa-solid fa-trash-can text-base"></i>
                    </button>
                </div>
            </div>
            <x-confirm-modal
                id="delete-tugas-{{ $t->id }}"
                title="Hapus Tugas"
                message="Apakah Anda yakin ingin menghapus tugas '{{ addslashes($t->nama_tugas) }}'? Tindakan ini tidak dapat dibatalkan."
                action="{{ route('tugas.destroy', $t->id) }}"
                method="DELETE"
                type="danger"
            />
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
