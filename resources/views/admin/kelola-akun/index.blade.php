@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Akun</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manajemen data para pengguna dan akun pegawai di dalam sistem.</p>
        </div>
        <div>
            <a href="{{ route('kelola-akun.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-100 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-base"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Departemen</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4 text-sm font-medium text-gray-800">{{ $user->nama_lengkap }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="p-4 text-sm text-gray-600">
                                <span class="px-2.5 py-1 text-xs font-medium bg-purple-50 text-purple-700 rounded-lg">
                                    {{ $user->nama_role }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-600">{{ $user->departemen->nama_departemen ?? '-' }}</td>
                            <td class="p-4 text-sm text-gray-600">
                                <span class="px-2.5 py-1 text-xs font-medium bg-indigo-50 text-[#3B28CC] rounded-lg">
                                    {{ $user->status_pegawai }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kelola-akun.show', $user->id) }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('kelola-akun.edit', $user->id) }}" class="p-2 text-gray-400 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" onclick="openModal('modal-hapus-{{ $user->id }}')" class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@foreach($users as $user)
    <x-confirm-modal
        id="modal-hapus-{{ $user->id }}"
        title="Hapus Akun Pengguna"
        message="Apakah Anda yakin ingin menghapus akun {{ $user->nama_lengkap }}? Tindakan ini tidak dapat dibatalkan."
        action="{{ route('kelola-akun.destroy', $user->id) }}"
        method="DELETE"
        type="danger"
    />
@endforeach

@endsection
