@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6 pb-10">

    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Utama</h1>
            <p class="text-sm text-gray-500 mt-0.5">Selamat datang kembali, <span class="font-semibold text-gray-700">{{ Auth::user()->nama_lengkap }}</span>. Berikut ringkasan sistem hari ini.</p>
        </div>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 shrink-0">

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pegawai Aktif</span>
                <h3 class="text-2xl font-bold text-green-600">{{ $pegawaiAktif }}</h3>
                <p class="text-[10px] text-gray-400">Pegawai aktif saat ini</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                <i class="fa-solid fa-user-check text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pegawai Non-aktif</span>
                <h3 class="text-2xl font-bold text-rose-500">{{ $pegawaiNonAktif }}</h3>
                <p class="text-[10px] text-gray-400">Pegawai non-aktif saat ini</p>
            </div>
            <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                <i class="fa-solid fa-user-xmark text-xl"></i>
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
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Presentase Kinerja</span>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 shrink-0">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600 shrink-0">
                <i class="fa-solid fa-user-tie text-base"></i>
            </div>
            <div>
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">Total Manager</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalManager }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600 shrink-0">
                <i class="fa-solid fa-user text-base"></i>
            </div>
            <div>
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">Total Staff</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalStaff }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 shrink-0">
                <i class="fa-solid fa-building text-base"></i>
            </div>
            <div>
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">Departemen</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalDepartemen }}</p>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Ringkasan per Departemen</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Jumlah tugas aktif dan selesai tiap departemen.</p>
                </div>
                <a href="{{ route('admin.tugas.index') }}" class="text-xs font-semibold text-[#3B28CC] hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($departemens as $dep)
                <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-gray-50/60 transition-colors">
                    <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-building text-[#3B28CC] text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $dep->nama_departemen }}</p>
                        <p class="text-[10px] text-gray-400">{{ $dep->users_count }} pengguna · {{ $dep->tugas_count }} tugas</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 text-right">
                        <div class="text-right">
                            <p class="text-xs font-bold text-green-600">{{ $dep->tugas_selesai_count }}</p>
                            <p class="text-[9px] text-gray-400">selesai</p>
                        </div>
                        <div class="w-px h-6 bg-gray-200"></div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-amber-600">{{ $dep->tugas_berjalan_count }}</p>
                            <p class="text-[9px] text-gray-400">berjalan</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-xs text-gray-400">Belum ada departemen.</div>
                @endforelse
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Tugas Terbaru</h2>
                    <p class="text-xs text-gray-400 mt-0.5">5 tugas yang paling baru dibuat.</p>
                </div>
                <a href="{{ route('admin.tugas.index') }}" class="text-xs font-semibold text-[#3B28CC] hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($tugasTerbaru as $t)
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
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $t->nama_tugas }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">
                            {{ $t->departemen->nama_departemen ?? '-' }}
                            &nbsp;·&nbsp;Deadline: {{ \Carbon\Carbon::parse($t->deadline_tugas)->format('d M Y') }}
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

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Pegawai Terbaru</h2>
                <p class="text-xs text-gray-400 mt-0.5">5 pengguna yang paling baru terdaftar.</p>
            </div>
            <a href="{{ route('kelola-akun.index') }}" class="text-xs font-semibold text-[#3B28CC] hover:underline">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Departemen</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($pegawaiTerbaru as $p)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                @if($p->foto_profil)
                                    <img src="{{ asset('storage/' . $p->foto_profil) }}" class="w-8 h-8 rounded-full object-cover border border-gray-100 shrink-0">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($p->nama_lengkap) }}&background=3B28CC&color=fff&size=64" class="w-8 h-8 rounded-full object-cover border border-gray-100 shrink-0">
                                @endif
                                <span class="font-semibold text-gray-800">{{ $p->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $p->email }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2.5 py-1 text-[10px] font-semibold bg-purple-50 text-purple-700 rounded-lg capitalize">
                                {{ $p->nama_role }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-600">{{ $p->departemen->nama_departemen ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-semibold rounded-lg
                                {{ $p->is_active ? 'bg-green-50 text-green-700' : 'bg-rose-50 text-rose-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $p->is_active ? 'bg-green-500' : 'bg-rose-500' }}"></span>
                                {{ $p->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-xs text-gray-400">Belum ada pegawai terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
