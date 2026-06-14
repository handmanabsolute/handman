@extends('layouts.app')

@section('title', 'Detail Profil Staff')

@section('content')
<div class="space-y-6 pb-12">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('staff-divisi.index') }}" class="w-10 h-10 border border-gray-200 bg-white rounded-xl flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Profil Staff</h1>
                <p class="text-sm text-gray-500 mt-0.5">Informasi profil, grup kerja, dan monitoring tugas staff.</p>
            </div>
        </div>
    </div>

    
    @if(session('success'))
        <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-100 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-600 text-base shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden space-y-6 p-6">
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="relative">
                    @if($staff->foto_profil)
                        <img src="{{ asset('storage/' . $staff->foto_profil) }}" class="w-24 h-24 rounded-full object-cover border-4 border-indigo-50 shadow-md">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($staff->nama_lengkap) }}&background=3B28CC&color=fff&size=128" class="w-24 h-24 rounded-full object-cover border-4 border-indigo-50 shadow-md">
                    @endif
                    <span class="absolute bottom-1 right-1 w-4 h-4 rounded-full border-2 border-white {{ $staff->is_active ? 'bg-green-500' : 'bg-rose-500' }}"></span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $staff->nama_lengkap }}</h2>
                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $staff->email }}</p>
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-indigo-50 text-[#3B28CC] border border-indigo-100 capitalize">
                        Role: {{ $staff->nama_role }}
                    </span>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-gray-50 text-gray-600 border border-gray-200">
                        {{ $staff->status_pegawai }}
                    </span>
                </div>
            </div>

            <hr class="border-gray-150/70">

            <div class="space-y-4 text-sm">
                <div>
                    <span class="block font-medium text-gray-400 text-xs">No. Telepon</span>
                    <span class="text-gray-800 font-semibold mt-0.5 block">{{ $staff->no_telp ?? '-' }}</span>
                </div>
                <div>
                    <span class="block font-medium text-gray-400 text-xs">Jenis Kelamin</span>
                    <span class="text-gray-800 font-semibold mt-0.5 block">{{ $staff->jenis_kelamin ?? '-' }}</span>
                </div>
                <div>
                    <span class="block font-medium text-gray-400 text-xs">Tanggal Lahir</span>
                    <span class="text-gray-800 font-semibold mt-0.5 block">
                        {{ $staff->tanggal_lahir ? \Carbon\Carbon::parse($staff->tanggal_lahir)->format('d F Y') : '-' }}
                    </span>
                </div>
                <div>
                    <span class="block font-medium text-gray-400 text-xs">Alamat</span>
                    <span class="text-gray-800 font-medium mt-0.5 block leading-relaxed">{{ $staff->alamat ?? '-' }}</span>
                </div>
                <div>
                    <span class="block font-medium text-gray-400 text-xs">Deskripsi Diri</span>
                    <p class="text-gray-600 mt-1 leading-relaxed bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs italic">
                        {{ $staff->deskripsi_user ?? 'Tidak ada deskripsi diri.' }}
                    </p>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2 space-y-6">

            
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="border-b border-gray-50 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900">Grup Kerja Staff</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Grup kerja yang saat ini diikuti oleh {{ $staff->nama_lengkap }}.</p>
                    </div>
                </div>

                
                @if($myGrups->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($myGrups as $grup)
                            <div class="border border-gray-100 bg-gray-50/50 p-4 rounded-xl flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $grup->nama_grup }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5 uppercase tracking-wider">{{ $grup->anggota->count() }} Anggota</p>
                                </div>
                                <button type="button" onclick="openModal('leave-group-{{ $grup->id }}')" class="w-8 h-8 rounded-lg border border-rose-100 hover:bg-rose-50 flex items-center justify-center text-rose-500 transition-colors" title="Keluarkan dari Grup">
                                    <i class="fa-solid fa-user-minus text-xs"></i>
                                </button>
                                <x-confirm-modal 
                                    id="leave-group-{{ $grup->id }}" 
                                    title="Keluarkan dari Grup" 
                                    message="Apakah Anda yakin ingin mengeluarkan staff ini dari grup '{{ addslashes($grup->nama_grup) }}'?" 
                                    action="{{ route('staff-divisi.leave-group', ['id' => $staff->id, 'grup_kerja_id' => $grup->id]) }}" 
                                    method="POST" 
                                    type="danger" 
                                />
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">Staff ini belum dimasukkan ke dalam grup kerja manapun.</p>
                @endif

                
                @if($grups->count() > 0)
                    <div class="pt-4 border-t border-gray-100">
                        <form id="form-join-group" action="{{ route('staff-divisi.join-group', $staff->id) }}" method="POST" class="flex flex-col sm:flex-row sm:items-end gap-3 max-w-md">
                            @csrf
                            <div class="flex-1 space-y-1.5">
                                <label for="grup_kerja_id" class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Masukkan ke Grup Kerja</label>
                                <select name="grup_kerja_id" id="grup_kerja_id" required class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all cursor-pointer">
                                    <option value="">-- Pilih Grup Kerja --</option>
                                    @foreach($grups as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama_grup }} ({{ $g->anggota->count() }} Anggota)</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-grup_kerja_id"></p>
                            </div>
                            <button type="submit" class="px-4 py-2.5 bg-[#3B28CC] text-white text-sm font-bold rounded-xl hover:bg-opacity-95 transition flex items-center justify-center gap-1.5 shrink-0 shadow-sm cursor-pointer">
                                <i class="fa-solid fa-user-plus text-xs"></i> Masukkan Grup
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="border-b border-gray-50 pb-3">
                    <h3 class="font-bold text-gray-900">Daftar Tugas Terkait</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Penugasan individu maupun departemen yang melibatkan staff ini.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                <th class="p-3">Nama Tugas</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Prioritas</th>
                                <th class="p-3">Deadline</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-xs text-gray-700">
                            @forelse($tugas as $t)
                                @php
                                    $statusConfig = match($t->status_tugas) {
                                        'Selesai'              => ['bg-green-50 text-green-700 border-green-150', 'fa-circle-check'],
                                        'Menunggu Persetujuan' => ['bg-blue-50 text-blue-700 border-blue-150', 'fa-hourglass-half'],
                                        'Revisi'               => ['bg-rose-50 text-rose-700 border-rose-150', 'fa-arrow-rotate-left'],
                                        default                => ['bg-gray-100 text-gray-500 border-gray-200', 'fa-clock'],
                                    };
                                    $prioritasColor = match($t->prioritas) {
                                        'Tinggi' => 'bg-red-50 text-red-700 border-red-150',
                                        'Sedang' => 'bg-orange-50 text-orange-700 border-orange-150',
                                        default  => 'bg-green-50 text-green-700 border-green-150',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/40 transition-colors">
                                    <td class="p-3">
                                        <a href="{{ route('tugas.show', $t->id) }}" class="font-bold text-gray-800 hover:text-[#3B28CC] hover:underline">
                                            {{ $t->nama_tugas }}
                                        </a>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 font-semibold rounded-md border {{ $t->kategoritugas === 'Kelompok' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                            {{ $t->kategoritugas === 'Kelompok' ? 'Departemen' : $t->kategoritugas }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 font-bold rounded-md border {{ $prioritasColor }}">{{ $t->prioritas }}</span>
                                    </td>
                                    <td class="p-3 text-gray-500 font-mono">
                                        {{ \Carbon\Carbon::parse($t->deadline_tugas)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="p-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 font-bold rounded-md border {{ $statusConfig[0] }}">
                                            <i class="fa-solid {{ $statusConfig[1] }} text-[9px]"></i>
                                            {{ $t->status_tugas ?? 'Belum Dikerjakan' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-400 italic">Belum ada tugas terkait untuk staff ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    initRealTimeValidation('form-join-group');
});
</script>
@endsection
