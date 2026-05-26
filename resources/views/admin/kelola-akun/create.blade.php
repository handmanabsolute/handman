@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Akun Baru</h1>
            <p class="text-sm text-gray-500 mt-0.5">Isi seluruh formulir di bawah ini untuk menambahkan pengguna baru.</p>
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
        <form action="{{ route('kelola-akun.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">No. Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Status Pegawai</label>
                    <input type="text" name="status_pegawai" value="{{ old('status_pegawai') }}" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Role</label>
                    <select name="nama_role" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                        <option value="manager" {{ old('nama_role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="staff" {{ old('nama_role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Departemen</label>
                    <div class="flex gap-2">
                        <select name="departemen_id" required class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                            <option value="">Pilih Departemen</option>
                            @foreach($departemens as $dept)
                                <option value="{{ $dept->id }}" {{ old('departemen_id') == $dept->id ? 'selected' : '' }}>{{ $dept->nama_departemen }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('departemen.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors whitespace-nowrap gap-2">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Tambah Departemen
                        </a>
                    </div>
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('alamat') }}</textarea>
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Deskripsi User</label>
                    <textarea name="deskripsi_user" rows="3" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('deskripsi_user') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
