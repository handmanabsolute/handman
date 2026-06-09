@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Departemen</h1>
            <p class="text-sm text-gray-500 mt-0.5">Tambah dan lihat kategori departemen untuk penempatan kerja pegawai.</p>
        </div>
        <div>
            <a href="{{ route('kelola-akun.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm lg:col-span-2">
            <h2 class="text-base font-bold text-gray-900 mb-4">Tambah Departemen Baru</h2>
            <form id="form-departemen" action="{{ route('departemen.store') }}" method="POST" class="space-y-5" data-redirect="{{ route('departemen.create') }}" novalidate>
                @csrf

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Nama Departemen</label>
                    <input type="text" name="nama_departemen" value="{{ old('nama_departemen') }}" required placeholder="Contoh: IT Support, HRD" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                    <p class="text-xs text-red-600 error-msg hidden" id="error-nama_departemen"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Deskripsi <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea name="deskripsi" rows="3" placeholder="Tuliskan deskripsi singkat..." class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('deskripsi') }}</textarea>
                    <p class="text-xs text-red-600 error-msg hidden" id="error-deskripsi"></p>
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="button" onclick="openModal('confirm-dept')" class="w-full inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors cursor-pointer">
                        Simpan Departemen
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm lg:col-span-3 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Daftar Departemen</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Departemen</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($departemens ?? \App\Models\Departemen::all() as $dept)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $dept->nama_departemen }}</td>
                                <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $dept->deskripsi_departemen ?? '-' }}</td>
                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('departemen.edit', $dept->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 bg-gray-50 border border-gray-200 hover:text-[#3B28CC] hover:bg-indigo-50 hover:border-indigo-200 transition-all">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <button type="button" onclick="openModal('delete-dept-{{ $dept->id }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 bg-gray-50 border border-gray-200 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-all cursor-pointer">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </td>
                            </tr>

                            <x-confirm-modal id="delete-dept-{{ $dept->id }}" title="Konfirmasi Hapus Departemen" message="Apakah Anda yakin ingin menghapus departemen ini? Semua data user yang terkait dengan departemen ini juga akan ikut terhapus secara otomatis." action="{{ route('departemen.destroy', $dept->id) }}" method="DELETE" type="danger" />
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-400">Belum ada data departemen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<x-confirm-modal id="confirm-dept" title="Konfirmasi Simpan Departemen" message="Apakah Anda yakin data departemen yang dimasukkan sudah benar?" action="executeGlobalAjaxSubmit('form-departemen', 'confirm-dept')" type="primary" />

<script>
    document.addEventListener('DOMContentLoaded', () => {
        initRealTimeValidation('form-departemen');
    });
</script>
@endsection
