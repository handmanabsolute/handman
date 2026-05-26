@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Akun Pengguna</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi data akun pegawai di bawah ini.</p>
        </div>
        <div>
            <a href="{{ route('kelola-akun.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 text-sm text-red-800 bg-red-50 border border-red-100 rounded-xl">
            <div class="font-medium mb-1">Terjadi kesalahan input:</div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('kelola-akun.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Password <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                        <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? (\Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d')) : '') }}" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Status Pegawai</label>
                    <input type="text" name="status_pegawai" value="{{ old('status_pegawai', $user->status_pegawai) }}" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Role</label>
                    <input type="text" name="nama_role" value="{{ old('nama_role', $user->nama_role) }}" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Departemen</label>
                    <select name="departemen_id" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                        @foreach($departemens as $dept)
                            <option value="{{ $dept->id }}" {{ old('departemen_id', $user->departemen_id) == $dept->id ? 'selected' : '' }}>{{ $dept->nama_departemen }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('alamat', $user->alamat) }}</textarea>
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Deskripsi User</label>
                    <textarea name="deskripsi_user" rows="3" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('deskripsi_user', $user->deskripsi_user) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors">
                    Perbarui Akun
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
