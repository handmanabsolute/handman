@extends('layouts.app')

@section('title', 'Tambah Tugas')

@section('content')
<div class="mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Tugas Baru</h1>
        <p class="text-sm text-gray-500">Silakan isi formulir di bawah ini untuk membuat penugasan baru.</p>
    </div>

    <form id="form_tugas" action="{{ route('tugas.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs space-y-6">
        @csrf

        <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700">Nama Tugas</label>
            <input type="text" name="nama_tugas" value="{{ old('nama_tugas') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-nama_tugas"></p>
        </div>

        <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700">Deskripsi Tugas</label>
            <textarea name="deskripsi" rows="5" placeholder="Detail Tugas....." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>{{ old('deskripsi') }}</textarea>
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-deskripsi"></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Tanggal Mulai</label>
                <input type="date" name="tanggal_tugas_date" value="{{ old('tanggal_tugas_date', now()->format('Y-m-d')) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-tanggal_tugas_date"></p>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-tanggal_tugas"></p>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Jam Mulai</label>
                <input type="time" name="tanggal_tugas_time" value="{{ old('tanggal_tugas_time', now()->format('H:i')) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Tanggal Deadline</label>
                <input type="date" name="deadline_tugas_date" value="{{ old('deadline_tugas_date') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-deadline_tugas_date"></p>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-deadline_tugas"></p>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Jam Deadline</label>
                <input type="time" name="deadline_tugas_time" value="{{ old('deadline_tugas_time', '23:59') }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Prioritas</label>
                <select name="prioritas" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
                    <option value="Rendah" {{ old('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                    <option value="Sedang" {{ old('prioritas') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Tinggi" {{ old('prioritas') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                </select>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-prioritas"></p>
            </div>

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-gray-700">Kategori Tugas</label>
                <select name="kategoritugas" id="kategoritugas" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]" required>
                    <option value="Individu" {{ old('kategoritugas') == 'Individu' ? 'selected' : '' }}>Individu</option>
                    <option value="Kelompok" {{ old('kategoritugas') == 'Kelompok' ? 'selected' : '' }}>Departemen</option>
                </select>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-kategoritugas"></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <div class="space-y-1.5 text-left" id="assignee_staff_container">
                <label class="text-sm font-semibold text-gray-700">Pilih Staff Penanggung Jawab</label>
                <select name="user_id" id="user_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]">
                    <option value=""> Pilih Staff </option>
                    @foreach($staffs as $staff)
                        <option value="{{ $staff->id }}" {{ old('user_id') == $staff->id ? 'selected' : '' }}>{{ $staff->nama_lengkap }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-user_id"></p>
            </div>

            
            <div class="space-y-1.5 text-left hidden" id="assignee_grup_container">
                <label class="text-sm font-semibold text-gray-700">Pilih Grup Kerja Penanggung Jawab</label>
                <select name="grup_kerja_id" id="grup_kerja_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]">
                    <option value=""> Pilih Grup Kerja </option>
                    @foreach($grups as $grup)
                        <option value="{{ $grup->id }}" {{ old('grup_kerja_id') == $grup->id ? 'selected' : '' }}>{{ $grup->nama_grup }} ({{ $grup->anggota->count() }} Anggota)</option>
                    @endforeach
                </select>
                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-grup_kerja_id"></p>
            </div>
        </div>

        <div class="space-y-3 pt-2">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-paperclip text-[#3B28CC]"></i> Dokumen Terkait Tugas
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative group border-2 border-dashed border-gray-200 hover:border-[#3B28CC] rounded-xl p-6 bg-gray-50/50 hover:bg-gray-50 transition-all text-center cursor-pointer flex flex-col items-center justify-center min-h-40">
                    <input type="file" name="gambar_file" id="gambar_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div id="container_preview_gambar" class="hidden absolute inset-0 bg-white rounded-xl p-4 z-20 space-y-2 justify-center">
                        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Preview Gambar</p>
                            <button type="button" id="hapus_gambar" class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-xs hover:bg-red-600 transition-colors">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-600 p-2 bg-gray-50 rounded-lg text-left">
                            <i class="fa-regular fa-image text-[#3B28CC]"></i>
                            <a id="link_preview_gambar" href="#" download class="font-medium text-[#3B28CC] hover:underline truncate max-w-[85%]" title="Klik untuk mendownload gambar"></a>
                        </div>
                    </div>
                    <div id="placeholder_gambar" class="flex flex-col items-center justify-center space-y-2">
                        <div class="w-10 h-10 bg-[#3B28CC] text-white flex items-center justify-center rounded-xl shadow-sm">
                            <i class="fa-regular fa-image text-lg"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-800">Upload Gambar</p>
                        <p class="text-xs text-gray-400">Supports JPG, PNG, WebP (Max 10MB)</p>
                    </div>
                </div>

                <div class="relative group border-2 border-dashed border-gray-200 hover:border-[#3B28CC] rounded-xl p-6 bg-gray-50/50 hover:bg-gray-50 transition-all text-center cursor-pointer flex flex-col items-center justify-center min-h-40">
                    <input type="file" name="nama_file" id="nama_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div id="container_preview_dokumen" class="hidden absolute inset-0 bg-white rounded-xl p-4 z-20 space-y-2 justify-center">
                        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Preview Dokumen</p>
                            <button type="button" id="hapus_dokumen" class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-xs hover:bg-red-600 transition-colors">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-600 p-2 bg-gray-50 rounded-lg text-left">
                            <i class="fa-regular fa-file text-[#3B28CC]"></i>
                            <a id="link_preview_dokumen" href="#" target="_blank" class="font-medium text-[#3B28CC] hover:underline truncate max-w-[85%]" title="Klik untuk membuka di tab baru"></a>
                        </div>
                    </div>
                    <div id="placeholder_dokumen" class="flex flex-col items-center justify-center space-y-2">
                        <div class="w-10 h-10 bg-blue-100 text-[#3B28CC] flex items-center justify-center rounded-xl">
                            <i class="fa-regular fa-file-lines text-lg"></i>
                        </div>
                        <p class="text-sm font-bold text-gray-800">Upload Dokumen</p>
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
            <input type="url" name="link_tugas" value="{{ old('link_tugas') }}" placeholder="Link Tugas" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC]">
            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-link_tugas"></p>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
            <a href="{{ route('tugas.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" id="btn_submit" class="px-5 py-2.5 bg-[#3B28CC] text-white rounded-xl text-sm font-semibold hover:bg-[#2c1fa3] transition-colors flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-regular fa-circle-check"></i> Buat Tugas
            </button>
        </div>
    </form>
</div>

<script>
    let gambarObjectUrl = null;
    let dokumenObjectUrl = null;
    const MAX_GAMBAR_SIZE = 10 * 1024 * 1024;
    const MAX_DOKUMEN_SIZE = 20 * 1024 * 1024;
    let gambarValid = true;
    let dokumenValid = true;

    function validasiForm() {
        const errorContainer = document.getElementById('pesan_error_kapasitas');
        const errorText = document.getElementById('teks_error_kapasitas');
        const btnSubmit = document.getElementById('btn_submit');

        if (!gambarValid) {
            errorText.textContent = "Ukuran file Gambar melebihi batas maksimum 10MB! File tidak dapat diupload.";
            errorContainer.classList.remove('hidden');
            errorContainer.classList.add('flex');
            btnSubmit.disabled = true;
        } else if (!dokumenValid) {
            errorText.textContent = "Ukuran file Dokumen melebihi batas maksimum 20MB! File tidak dapat diupload.";
            errorContainer.classList.remove('hidden');
            errorContainer.classList.add('flex');
            btnSubmit.disabled = true;
        } else {
            errorContainer.classList.remove('flex');
            errorContainer.classList.add('hidden');
            btnSubmit.disabled = false;
        }
    }

    document.getElementById('gambar_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (gambarObjectUrl) {
            URL.revokeObjectURL(gambarObjectUrl);
            gambarObjectUrl = null;
        }

        if (file) {
            gambarValid = file.size <= MAX_GAMBAR_SIZE;
            gambarObjectUrl = URL.createObjectURL(file);

            const linkElement = document.getElementById('link_preview_gambar');
            linkElement.setAttribute('href', gambarObjectUrl);
            linkElement.setAttribute('download', file.name);
            linkElement.textContent = file.name + ' (Klik untuk download)';

            const previewContainer = document.getElementById('container_preview_gambar');
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('flex', 'flex-col');
        } else {
            gambarValid = true;
        }
        validasiForm();
    });

    document.getElementById('hapus_gambar').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('gambar_file').value = '';
        const previewContainer = document.getElementById('container_preview_gambar');
        previewContainer.classList.remove('flex', 'flex-col');
        previewContainer.classList.add('hidden');
        if (gambarObjectUrl) {
            URL.revokeObjectURL(gambarObjectUrl);
            gambarObjectUrl = null;
        }
        gambarValid = true;
        validasiForm();
    });

    document.getElementById('nama_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (dokumenObjectUrl) {
            URL.revokeObjectURL(dokumenObjectUrl);
            dokumenObjectUrl = null;
        }

        if (file) {
            dokumenValid = file.size <= MAX_DOKUMEN_SIZE;
            dokumenObjectUrl = URL.createObjectURL(file);

            const linkElement = document.getElementById('link_preview_dokumen');
            linkElement.setAttribute('href', dokumenObjectUrl);
            linkElement.textContent = file.name + ' (Klik untuk preview)';

            const previewContainer = document.getElementById('container_preview_dokumen');
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('flex', 'flex-col');
        } else {
            dokumenValid = true;
        }
        validasiForm();
    });

    document.getElementById('hapus_dokumen').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('nama_file').value = '';
        const previewContainer = document.getElementById('container_preview_dokumen');
        previewContainer.classList.remove('flex', 'flex-col');
        previewContainer.classList.add('hidden');
        if (dokumenObjectUrl) {
            URL.revokeObjectURL(dokumenObjectUrl);
            dokumenObjectUrl = null;
        }
        dokumenValid = true;
        validasiForm();
    });

    // Toggle assignee fields based on kategoritugas
    const kategoritugasSelect = document.getElementById('kategoritugas');
    const staffContainer = document.getElementById('assignee_staff_container');
    const grupContainer = document.getElementById('assignee_grup_container');
    const staffSelect = document.getElementById('user_id');
    const grupSelect = document.getElementById('grup_kerja_id');

    function toggleAssigneeFields() {
        if (kategoritugasSelect.value === 'Individu') {
            staffContainer.classList.remove('hidden');
            grupContainer.classList.add('hidden');
            staffSelect.disabled = false;
            staffSelect.required = true;
            grupSelect.disabled = true;
            grupSelect.required = false;
            grupSelect.value = '';
        } else if (kategoritugasSelect.value === 'Kelompok') {
            staffContainer.classList.add('hidden');
            grupContainer.classList.remove('hidden');
            staffSelect.disabled = true;
            staffSelect.required = false;
            staffSelect.value = '';
            grupSelect.disabled = false;
            grupSelect.required = true;
        } else {
            staffContainer.classList.add('hidden');
            grupContainer.classList.add('hidden');
            staffSelect.disabled = true;
            staffSelect.required = false;
            grupSelect.disabled = true;
            grupSelect.required = false;
        }
    }

    if (kategoritugasSelect) {
        kategoritugasSelect.addEventListener('change', toggleAssigneeFields);
        toggleAssigneeFields();
    }

    document.addEventListener('DOMContentLoaded', () => {
        initRealTimeValidation('form_tugas');
    });
</script>
@endsection
