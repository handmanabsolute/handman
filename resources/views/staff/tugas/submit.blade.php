@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Form Pengumpulan Tugas</h1>
            <p class="text-sm text-gray-500 mt-0.5">Unggah berkas atau lampirkan tautan hasil kerja Anda untuk dievaluasi oleh manajer.</p>
        </div>
        <div>
            <a href="{{ route('staff.tugas.show', $tugas->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali ke Detail
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <div>
                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-indigo-50 text-[#3B28CC] border border-indigo-100">
                    {{ $tugas->kategoritugas }}
                </span>
                <h2 class="text-lg font-bold text-gray-800 mt-2">{{ $tugas->nama_tugas }}</h2>
            </div>

            <div class="space-y-4 text-sm border-t border-gray-50 pt-4">
                <div>
                    <span class="block font-medium text-gray-400 text-xs">Batas Akhir Selesai</span>
                    <span class="text-red-600 font-semibold block mt-0.5">
                        {{ \Carbon\Carbon::parse($tugas->deadline_tugas)->format('d F Y, H:i') }}
                    </span>
                </div>
                <div>
                    <span class="block font-medium text-gray-400 text-xs">Status Sesi</span>
                    @if(\Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($tugas->deadline_tugas)))
                        <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-medium bg-red-50 text-red-700 rounded-lg">Terlambat</span>
                    @else
                        <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-medium bg-green-50 text-green-700 rounded-lg">Terbuka</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-5 border-b border-gray-50">
                <h3 class="font-bold text-gray-800">Formulir Lampiran Hasil Kerja</h3>
            </div>

            <form action="{{ route('tugas.submit', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl">
                        <ul class="list-disc list-inside text-xs text-rose-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400">Unggah Gambar (Opsional)</label>
                        <div class="relative flex flex-col items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors p-4">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <i class="fa-solid fa-image text-gray-400 text-xl mb-2"></i>
                                    <p id="label_gambar" class="text-xs text-gray-500 font-medium">Format: JPEG, PNG, JPG, WEBP</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Maksimal 10MB</p>
                                </div>
                                <input type="file" id="gambar_file" name="gambar_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="checkFile(this, 'link_preview_gambar', 'label_gambar')" />
                            </label>
                            <button type="button" id="link_preview_gambar" class="hidden mt-2 text-xs font-medium text-[#3B28CC] hover:underline items-center gap-1" onclick="showFile('gambar_file')">
                                <i class="fa-solid fa-eye text-[10px]"></i> Preview Gambar
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400">Unggah Dokumen (Opsional)</label>
                        <div class="relative flex flex-col items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors p-4">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <i class="fa-solid fa-file-pdf text-gray-400 text-xl mb-2"></i>
                                    <p id="label_dokumen" class="text-xs text-gray-500 font-medium">Format: PDF, DOCX, XLSX, PPTX, TXT</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Maksimal 20MB</p>
                                </div>
                                <input type="file" id="nama_file" name="nama_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="checkFile(this, 'link_preview_dokumen', 'label_dokumen')" />
                            </label>
                            <button type="button" id="link_preview_dokumen" class="hidden mt-2 text-xs font-medium text-emerald-600 hover:underline items-center gap-1" onclick="showFile('nama_file')">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Preview Dokumen
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="link_tugas" class="block text-xs font-bold uppercase tracking-wider text-gray-400">Tautan Kerja / Workspace Link (Opsional)</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-link text-sm"></i>
                        </div>
                        <input type="url" name="link_tugas" id="link_tugas" value="{{ old('link_tugas') }}" class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all placeholder-gray-400" placeholder="https://example.com/your-work-link">
                    </div>
                </div>

                <div class="flex items-center justify-end pt-5 border-t border-gray-100 gap-3">
                    <a href="{{ route('staff.tugas.show', $tugas->id) }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        Kirim Tugas Sekarang
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    function checkFile(input, linkId, labelId) {
        const link = document.getElementById(linkId);
        const label = document.getElementById(labelId);
        if (input.files && input.files[0]) {
            const name = input.files[0].name;
            label.innerText = name.length > 25 ? name.substring(0, 22) + '...' : name;
            label.className = "text-xs text-gray-800 font-bold";
            link.style.display = 'inline-flex';
        } else {
            link.style.display = 'none';
        }
    }

    function showFile(inputId) {
        const input = document.getElementById(inputId);
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const url = URL.createObjectURL(file);
            window.open(url, '_blank');
        }
    }
</script>
@endsection
