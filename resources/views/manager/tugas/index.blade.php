@extends('layouts.app')

@section('title', 'Kelola Tugas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Tugas</h1>
            <p class="text-sm text-gray-500">Manajemen daftar tugas, kategori, dan batas waktu pengerjaan.</p>
        </div>
        <a href="{{ route('tugas.create') }}" class="bg-[#3B28CC] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#2c1fa3] transition-colors flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Tugas
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
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
                                {{ $t->kategoritugas }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $t->prioritas === 'Tinggi' ? 'bg-red-50 text-red-600' : ($t->prioritas === 'Sedang' ? 'bg-orange-50 text-orange-600' : 'bg-green-50 text-green-600') }}">
                                {{ $t->prioritas }}
                            </span>
                        </td>
                        <td class="p-4 text-xs text-gray-500">{{ \Carbon\Carbon::parse($t->deadline_tugas)->format('d M Y, H:i') }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $t->status_tugas === 'Selesai' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ $t->status_tugas ?? 'Belum Selesai' }}
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
                                <form action="{{ route('tugas.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                        <i class="fa-solid fa-trash-can text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400">
                            Tidak ada data tugas yang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
