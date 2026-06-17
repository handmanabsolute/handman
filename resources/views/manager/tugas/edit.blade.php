@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
<div class="mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Tugas</h1>
        <p class="text-sm text-gray-500">Silakan perbarui formulir di bawah ini untuk mengubah data penugasan.</p>
    </div>

    <form id="form_tugas" action="{{ route('tugas.update', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700">Nama Tugas</label>
            <input type="text" name="nama_tugas" value="{{ old('nama_tugas', $tugas->nama_tugas) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-nama_tugas"></p>
        </div>

        <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700">Deskripsi Tugas</label>
            <textarea name="deskripsi" rows="5" placeholder="Detail Tugas....." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-deskripsi"></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Tanggal Mulai</label>
                <input type="date" name="tanggal_tugas_date" value="{{ old('tanggal_tugas_date', \Carbon\Carbon::parse($tugas->tanggal_tugas)->format('Y-m-d')) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-tanggal_tugas_date"></p>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-tanggal_tugas"></p>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Jam Mulai</label>
                <input type="time" name="tanggal_tugas_time" value="{{ old('tanggal_tugas_time', \Carbon\Carbon::parse($tugas->tanggal_tugas)->format('H:i')) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Tanggal Deadline</label>
                <input type="date" name="deadline_tugas_date" value="{{ old('deadline_tugas_date', \Carbon\Carbon::parse($tugas->deadline_tugas)->format('Y-m-d')) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-deadline_tugas_date"></p>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-deadline_tugas"></p>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Jam Deadline</label>
                <input type="time" name="deadline_tugas_time" value="{{ old('deadline_tugas_time', \Carbon\Carbon::parse($tugas->deadline_tugas)->format('H:i')) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Prioritas</label>
                <select name="prioritas" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
                    <option value="Rendah" {{ old('prioritas', $tugas->prioritas) == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                    <option value="Sedang" {{ old('prioritas', $tugas->prioritas) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Tinggi" {{ old('prioritas', $tugas->prioritas) == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                </select>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-prioritas"></p>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Kategori Tugas</label>
                <select name="kategoritugas" id="kategoritugas" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
                    <option value="Individu" {{ old('kategoritugas', $tugas->kategoritugas) == 'Individu' ? 'selected' : '' }}>Individu</option>
                    <option value="Kelompok" {{ old('kategoritugas', $tugas->kategoritugas) == 'Kelompok' ? 'selected' : '' }}>Departemen</option>
                </select>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-kategoritugas"></p>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Status Tugas</label>
                <select name="status_tugas" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
                    <option value="Belum Dikerjakan" {{ old('status_tugas', $tugas->status_tugas) == 'Belum Dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                    <option value="Revisi" {{ old('status_tugas', $tugas->status_tugas) == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                    <option value="Menunggu Persetujuan" {{ old('status_tugas', $tugas->status_tugas) == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="Selesai" {{ old('status_tugas', $tugas->status_tugas) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-status_tugas"></p>
            </div>
        </div>

        <div class="space-y-1.5 text-left" id="assignee_staff_container">
            <label class="text-sm font-semibold text-gray-700">Pilih Staff Penanggung Jawab</label>
            <select name="user_id" id="user_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]">
                <option value=""> Pilih Staff </option>
                @foreach($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ old('user_id', $tugas->detailTugas->user_id ?? '') == $staff->id ? 'selected' : '' }}>{{ $staff->nama_lengkap }}</option>
                @endforeach
            </select>
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-user_id"></p>
        </div>

        
        <div class="space-y-1.5 text-left hidden" id="assignee_grup_container">
            <label class="text-sm font-semibold text-gray-700">Pilih Grup Kerja Penanggung Jawab</label>
            <select name="grup_kerja_id" id="grup_kerja_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]">
                <option value=""> Pilih Grup Kerja </option>
                @foreach($grups as $grup)
                    <option value="{{ $grup->id }}" {{ old('grup_kerja_id', $tugas->detailTugas->grup_kerja_id ?? '') == $grup->id ? 'selected' : '' }}>{{ $grup->nama_grup }} ({{ $grup->anggota->count() }} Anggota)</option>
                @endforeach
            </select>
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-grup_kerja_id"></p>
        </div>

        <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700">Catatan Revisi <span class="text-xs font-normal text-gray-400">(Opsional)</span></label>
            <textarea name="catatan_revisi" rows="3" placeholder="Tambahkan catatan jika ada perbaikan yang perlu dilakukan....." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]">{{ old('catatan_revisi', $tugas->catatan_revisi) }}</textarea>
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-catatan_revisi"></p>
        </div>

        <div class="space-y-3 pt-2">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-paperclip text-[#3B28CC]"></i> Dokumen Terkait Tugas
            </h3>

            @php
                $lampiran = $tugas->lampirans->first();
                $namaGambar = $lampiran && $lampiran->gambar_file ? basename($lampiran->gambar_file) : null;
                $namaDokumen = $lampiran && $lampiran->nama_file ? basename($lampiran->nama_file) : null;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative group border-2 border-dashed border-gray-200 hover:border-[#3B28CC] rounded-xl p-6 bg-gray-50/50 hover:bg-gray-50 transition-all text-center cursor-pointer flex flex-col items-center justify-center min-h-40">
                    <input type="file" name="gambar_file" id="gambar_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div id="container_preview_gambar" class="{{ $namaGambar ? 'flex flex-col' : 'hidden' }} absolute inset-0 bg-white rounded-xl p-4 z-20 space-y-2 justify-center">
                        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Preview Gambar</p>
                            <button type="button" id="hapus_gambar" class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-xs hover:bg-red-600 transition-colors">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-600 p-2 bg-gray-50 rounded-lg text-left">
                            <i class="fa-regular fa-image text-[#3B28CC]"></i>
                            <a id="link_preview_gambar" href="{{ $namaGambar ? asset('storage/' . $lampiran->gambar_file) : '#' }}" target="_blank" class="font-medium text-[#3B28CC] hover:underline truncate max-w-[85%]" title="Klik untuk preview gambar">
                                {{ $namaGambar ?? '' }}
                            </a>
                        </div>
                    </div>
                    <div id="placeholder_gambar" class="flex flex-col items-center justify-center space-y-2">
                        <div class="w-10 h-10 bg-[#3B28CC] text-white flex items-center justify-center rounded-xl shadow-sm">
                            <i class="fa-regular fa-image text-lg"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-800">Upload Gambar Baru</p>
                        <p class="text-xs text-gray-400">Supports JPG, PNG, WebP (Max 10MB)</p>
                    </div>
                </div>

                <div class="relative group border-2 border-dashed border-gray-200 hover:border-[#3B28CC] rounded-xl p-6 bg-gray-50/50 hover:bg-gray-50 transition-all text-center cursor-pointer flex flex-col items-center justify-center min-h-40">
                    <input type="file" name="nama_file" id="nama_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div id="container_preview_dokumen" class="{{ $namaDokumen ? 'flex flex-col' : 'hidden' }} absolute inset-0 bg-white rounded-xl p-4 z-20 space-y-2 justify-center">
                        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Preview Dokumen</p>
                            <button type="button" id="hapus_dokumen" class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-xs hover:bg-red-600 transition-colors">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-600 p-2 bg-gray-50 rounded-lg text-left">
                            <i class="fa-regular fa-file text-[#3B28CC]"></i>
                            <a id="link_preview_dokumen" href="{{ $namaDokumen ? asset('storage/' . $lampiran->nama_file) : '#' }}" target="_blank" class="font-medium text-[#3B28CC] hover:underline truncate max-w-[85%]" title="Klik untuk membuka di tab baru">
                                {{ $namaDokumen ?? '' }}
                            </a>
                        </div>
                    </div>
                    <div id="placeholder_dokumen" class="flex flex-col items-center justify-center space-y-2">
                        <div class="w-10 h-10 bg-blue-100 text-[#3B28CC] flex items-center justify-center rounded-xl">
                            <i class="fa-regular fa-file-lines text-lg"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-800">Upload Dokumen Baru</p>
                        <p class="text-xs text-gray-400">Maximum 5 files per task (Max 20MB)</p>
                    </div>
                </div>
            </div>
            <div id="pesan_error_kapasitas" class="hidden bg-red-50 border border-red-200 text-red-600 px-4 py-2.5 rounded-xl text-xs font-semibold items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span id="teks_error_kapasitas">Ukuran file melebihi kapasitas maksimum! File tidak dapat diupload.</span>
            </div>
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-gambar_file"></p>
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-nama_file"></p>
        </div>

        <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-link text-[#3B28CC]"></i> Link Tugas
            </label>
            <input type="url" name="link_tugas" value="{{ old('link_tugas', $lampiran->link_tugas ?? '') }}" placeholder="Link Tugas" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]">
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-link_tugas"></p>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
            <a href="{{ route('tugas.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" id="btn_submit" class="px-5 py-2.5 bg-[#3B28CC] text-white rounded-xl text-sm font-semibold hover:bg-[#2c1fa3] transition-colors flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-regular fa-circle-check"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>


@endsection
