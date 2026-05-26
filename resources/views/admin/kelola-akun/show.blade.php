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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center text-center space-y-4">
            <div class="w-24 h-24 bg-indigo-50 rounded-2xl flex items-center justify-center text-[#3B28CC] border border-indigo-100">
                @if($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Profil" class="w-full h-full object-cover rounded-2xl">
                @else
                    <i class="fa-solid fa-user text-4xl"></i>
                @endif
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $user->nama_lengkap }}</h2>
                <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-medium bg-purple-50 text-purple-700 rounded-lg">{{ $user->nama_role }}</span>
            </div>
            <p class="text-sm text-gray-400 max-w-xs">{{ $user->deskripsi_user ?? 'Tidak ada deskripsi tambahan mengenai pengguna ini.' }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-5 border-b border-gray-50">
                <h3 class="font-bold text-gray-800">Informasi Pribadi & Pekerjaan</h3>
            </div>
            <div class="divide-y divide-gray-50">
                <div class="grid grid-cols-3 p-4 text-sm">
                    <span class="font-medium text-gray-400">ID Pengguna (ULID)</span>
                    <span class="col-span-2 text-gray-800 font-mono text-xs">{{ $user->id }}</span>
                </div>
                <div class="grid grid-cols-3 p-4 text-sm">
                    <span class="font-medium text-gray-400">Email Utama</span>
                    <span class="col-span-2 text-gray-800">{{ $user->email }}</span>
                </div>
                <div class="grid grid-cols-3 p-4 text-sm">
                    <span class="font-medium text-gray-400">No. Telepon</span>
                    <span class="col-span-2 text-gray-800">{{ $user->no_telp ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-3 p-4 text-sm">
                    <span class="font-medium text-gray-400">Jenis Kelamin</span>
                    <span class="col-span-2 text-gray-800">{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="grid grid-cols-3 p-4 text-sm">
                    <span class="font-medium text-gray-400">Tanggal Lahir</span>
                    <span class="col-span-2 text-gray-800">{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d F Y') : '-' }}</span>
                </div>
                <div class="grid grid-cols-3 p-4 text-sm">
                    <span class="font-medium text-gray-400">Departemen</span>
                    <span class="col-span-2 text-gray-800 font-semibold">{{ $user->departemen->nama_departemen ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-3 p-4 text-sm">
                    <span class="font-medium text-gray-400">Status Pegawai</span>
                    <span class="col-span-2 text-gray-800">{{ $user->status_pegawai }}</span>
                </div>
                <div class="grid grid-cols-3 p-4 text-sm">
                    <span class="font-medium text-gray-400">Alamat Tempat Tinggal</span>
                    <span class="col-span-2 text-gray-800">{{ $user->alamat ?? '-' }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
