@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Akun</h1>
            <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap profil pengguna beserta detail unit departemen.</p>
        </div>
        <div>
            <a href="{{ route('kelola-akun.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        
        <div class="h-2 bg-[#3B28CC]"></div>
        
        <div class="p-6 sm:p-8 space-y-8">
            
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                
                <div class="relative shrink-0">
                    @if($user->foto_profil)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Avatar" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover shadow-sm border-4 border-indigo-50 bg-white">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama_lengkap ?? $user->email) }}&background=3B28CC&color=fff&size=128" alt="Avatar" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover shadow-sm border-4 border-indigo-50">
                    @endif
                </div>

                
                <div class="flex-1 text-center md:text-left space-y-3">
                    <div class="space-y-1">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $user->nama_lengkap }}</h2>
                        <p class="text-sm text-gray-500 font-medium">{{ $user->email }}</p>
                    </div>

                    <div class="flex flex-wrap gap-2 justify-center md:justify-start pt-1">
                        <span class="px-3 py-1 text-xs font-semibold bg-purple-50 text-purple-700 rounded-full border border-purple-100 uppercase tracking-wider">
                            {{ $user->nama_role }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold bg-indigo-50 text-[#3B28CC] rounded-full border border-indigo-100">
                            {{ $user->departemen->nama_departemen ?? '-' }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-full border border-emerald-100 capitalize">
                            {{ $user->status_pegawai }}
                        </span>
                    </div>

                    @if($user->deskripsi_user)
                        <div class="pt-3 max-w-2xl text-sm text-gray-600 leading-relaxed mx-auto md:mx-0">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Deskripsi User</span>
                            {{ $user->deskripsi_user }}
                        </div>
                    @endif
                </div>
            </div>

            
            <div class="border-t border-gray-100"></div>

            
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-address-card text-[#3B28CC]"></i>
                        Detail Informasi Pengguna
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    

                    <div class="space-y-1 p-4 bg-gray-50/50 rounded-xl border border-gray-100">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider block">Email Utama</span>
                        <p class="text-sm font-semibold text-gray-800">{{ $user->email }}</p>
                    </div>

                    <div class="space-y-1 p-4 bg-gray-50/50 rounded-xl border border-gray-100">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider block">No. Telepon</span>
                        <p class="text-sm font-semibold text-gray-800">{{ $user->no_telp ?? '-' }}</p>
                    </div>

                    <div class="space-y-1 p-4 bg-gray-50/50 rounded-xl border border-gray-100">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider block">Jenis Kelamin</span>
                        <p class="text-sm font-semibold text-gray-800">{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>

                    <div class="space-y-1 p-4 bg-gray-50/50 rounded-xl border border-gray-100">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider block">Tanggal Lahir</span>
                        <p class="text-sm font-semibold text-gray-800">{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d F Y') : '-' }}</p>
                    </div>

                    <div class="space-y-1 p-4 bg-gray-50/50 rounded-xl border border-gray-100">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider block">Status Pegawai</span>
                        <p class="text-sm font-semibold text-gray-800 capitalize">{{ $user->status_pegawai }}</p>
                    </div>

                    <div class="space-y-1 p-4 bg-gray-50/50 rounded-xl border border-gray-100 md:col-span-2 lg:col-span-3">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider block">Alamat Tempat Tinggal</span>
                        <p class="text-sm font-semibold text-gray-800 leading-relaxed">{{ $user->alamat ?? '-' }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
