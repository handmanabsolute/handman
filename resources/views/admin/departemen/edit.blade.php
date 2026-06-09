@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Departemen</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui data kategori penempatan kerja pegawai.</p>
        </div>
        <div>
            <a href="{{ route('departemen.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm max-w-2xl">
        <form id="form-edit-departemen" action="{{ route('departemen.update', $departemen->id) }}" method="POST" class="space-y-6" data-redirect="{{ route('departemen.create') }}" novalidate>
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Nama Departemen</label>
                    <input type="text" name="nama_departemen" value="{{ old('nama_departemen', $departemen->nama_departemen) }}" required placeholder="Contoh: IT Support, HRD" class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                    <p class="text-xs text-red-600 error-msg hidden" id="error-nama_departemen"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700">Deskripsi <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea name="deskripsi" rows="4" placeholder="Tuliskan deskripsi singkat..." class="w-full px-4 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('deskripsi', $departemen->deskripsi_departemen) }}</textarea>
                    <p class="text-xs text-red-600 error-msg hidden" id="error-deskripsi"></p>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-gray-100 gap-3">
                <a href="{{ route('departemen.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors">
                    Batal
                </a>
                <button type="button" onclick="openModal('confirm-edit-dept')" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

<x-confirm-modal id="confirm-edit-dept" title="Konfirmasi Perbarui Departemen" message="Apakah Anda yakin ingin menyimpan perubahan pada data departemen ini?" action="executeGlobalAjaxSubmit('form-edit-departemen', 'confirm-edit-dept')" type="amber" />

<script>
    document.addEventListener('DOMContentLoaded', () => {
        initRealTimeValidation('form-edit-departemen');
    });
</script>
@endsection
