@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Departemen Baru</h1>
            <p class="text-sm text-gray-500 mt-0.5">Buat kategori departemen baru untuk penempatan kerja pegawai.</p>
        </div>
        <div>
            <a href="{{ route('kelola-akun.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali ke Tambah Akun
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

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm max-w-2xl">
        <form action="{{ route('departemen.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-5">
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Nama Departemen</label>
                    <input type="text" name="nama_departemen" value="{{ old('nama_departemen') }}" required placeholder="Contoh: IT Support, HRD, Keuangan" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Deskripsi <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea name="deskripsi" rows="4" placeholder="Tuliskan deskripsi singkat mengenai cakupan kerja departemen..." class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors">
                    Simpan Departemen
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
