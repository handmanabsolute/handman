@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Tugas</h1>
            <p class="text-sm text-gray-500 mt-0.5">Tinjau parameter tugas dan lakukan pengumpulan hasil kerja sebelum batas waktu.</p>
        </div>
        <div>
            <a href="{{ route('staff.tugas.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>

    @if($tugas->status_tugas == 'Revisi' && $tugas->catatan_revisi)
        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-700 shrink-0 mt-0.5">
                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-rose-900">Catatan Revisi dari Manajer:</h4>
                <p class="text-xs text-rose-700 mt-0.5 leading-relaxed">{{ $tugas->catatan_revisi }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-6">

        <div class="pb-6 border-b border-gray-100 space-y-6">
            <div>
                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-indigo-50 text-[#3B28CC] border border-indigo-100">
                    {{ $tugas->kategoritugas === 'Kelompok' ? 'Departemen' : $tugas->kategoritugas }}
                </span>
                <h2 class="text-xl font-bold text-gray-900 mt-2.5">{{ $tugas->nama_tugas }}</h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 bg-gray-50/50 p-4 rounded-xl border border-gray-100/50 text-sm">
                <div>
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Prioritas</span>
                    @if($tugas->prioritas == 'Tinggi')
                        <span class="inline-block mt-1.5 px-2.5 py-0.5 text-xs font-bold bg-red-50 text-red-700 rounded-lg border border-red-100">{{ $tugas->prioritas }}</span>
                    @elseif($tugas->prioritas == 'Sedang')
                        <span class="inline-block mt-1.5 px-2.5 py-0.5 text-xs font-bold bg-amber-50 text-amber-700 rounded-lg border border-amber-100">{{ $tugas->prioritas }}</span>
                    @else
                        <span class="inline-block mt-1.5 px-2.5 py-0.5 text-xs font-bold bg-green-50 text-green-700 rounded-lg border border-green-100">{{ $tugas->prioritas }}</span>
                    @endif
                </div>
                <div>
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Tanggal Diberikan</span>
                    <span class="text-gray-800 font-medium block mt-1.5">{{ \Carbon\Carbon::parse($tugas->tanggal_tugas)->format('d F Y, H:i') }}</span>
                </div>
                <div>
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Batas Akhir Selesai</span>
                    <span class="text-red-600 font-semibold block mt-1.5">{{ \Carbon\Carbon::parse($tugas->deadline_tugas)->format('d F Y, H:i') }}</span>
                </div>
                <div>
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Status Saat Ini</span>
                    <div class="mt-1.5">
                        @if($tugas->status_tugas == 'Selesai')
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-green-50 text-green-700 rounded-lg border border-green-100">Selesai</span>
                        @elseif($tugas->status_tugas == 'Menunggu Persetujuan')
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-blue-50 text-blue-700 rounded-lg border border-blue-100">Menunggu Persetujuan</span>
                        @elseif($tugas->status_tugas == 'Revisi')
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-rose-50 text-rose-700 rounded-lg border border-rose-100">Revisi</span>
                        @else
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-gray-50 text-gray-700 rounded-lg border border-gray-200">Belum Dikerjakan</span>
                        @endif
                    </div>
                </div>
                @if($tugas->kategoritugas === 'Individu' && $tugas->detailTugas && $tugas->detailTugas->user)
                <div>
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Penerima Tugas</span>
                    <span class="text-gray-800 font-semibold block mt-1.5">{{ $tugas->detailTugas->user->nama_lengkap }}</span>
                </div>
                @elseif($tugas->kategoritugas === 'Kelompok' && $tugas->detailTugas && $tugas->detailTugas->grupKerja)
                <div>
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Penerima Tugas</span>
                    <span class="text-gray-800 font-semibold block mt-1.5">{{ $tugas->detailTugas->grupKerja->nama_grup }}</span>
                </div>
                @endif
            </div>
        </div>
            
            
            <div class="space-y-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Instruksi Kerja</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line bg-gray-50 p-4 rounded-xl border border-gray-100 leading-relaxed">
                    {{ $tugas->deskripsi }}
                </p>
            </div>

            <hr class="border-gray-100">

            
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Berkas Pendukung dari Manajer</h4>
                
                @php
                    $lampiranManager = $tugas->lampirans->filter(function($item) use ($tugas) {
                        if ($item->gambar_file && str_contains($item->gambar_file, 'pengumpulan/')) {
                            return false;
                        }
                        if ($item->nama_file && str_contains($item->nama_file, 'pengumpulan/')) {
                            return false;
                        }
                        if (!$item->gambar_file && !$item->nama_file && $item->link_tugas) {
                            return $item->created_at->diffInSeconds($tugas->created_at) < 15;
                        }
                        return true;
                    });
                @endphp

                @if($lampiranManager->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($lampiranManager as $lampiran)
                            @if($lampiran->gambar_file)
                                <div class="border border-gray-100 rounded-xl overflow-hidden p-2 bg-gray-50 flex flex-col space-y-2">
                                    <img src="{{ asset('storage/' . $lampiran->gambar_file) }}" class="w-full h-32 object-cover rounded-lg" alt="Gambar Instruksi">
                                    <a href="{{ asset('storage/' . $lampiran->gambar_file) }}" target="_blank" class="text-xs font-medium text-[#3B28CC] hover:underline px-1 flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Lihat Gambar Penuh
                                    </a>
                                </div>
                            @endif

                            @if($lampiran->nama_file)
                                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-[#3B28CC] flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-file-lines text-base"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-medium text-gray-800 truncate">{{ basename($lampiran->nama_file) }}</p>
                                            <p class="text-[10px] text-gray-400 uppercase font-medium">{{ pathinfo($lampiran->nama_file, PATHINFO_EXTENSION) }} File</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $lampiran->nama_file) }}" download class="w-8 h-8 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors shrink-0">
                                        <i class="fa-solid fa-download text-xs"></i>
                                    </a>
                                </div>
                            @endif

                            @if($lampiran->link_tugas)
                                <div class="border border-gray-100 p-4 rounded-xl bg-gray-50 flex items-center justify-between sm:col-span-2">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-link text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-medium text-gray-800">Tautan Eksternal / Workspace</p>
                                            <p class="text-[10px] text-gray-400 truncate max-w-md">{{ $lampiran->link_tugas }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $lampiran->link_tugas }}" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-[#3B28CC] bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors gap-1 shrink-0">
                                        Buka Link <i class="fa-solid fa-external-link text-[10px]"></i>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">Tidak ada lampiran dokumen atau gambar pendukung dari manajer.</p>
                @endif
            </div>

            @if($tugas->status_tugas !== 'Belum Dikerjakan')
                <hr class="border-gray-100">

                
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Berkas Pengumpulan Hasil Kerja Anda</h4>
                    
                    @php
                        $pengumpulanStaff = $tugas->lampirans->filter(function($item) use ($tugas) {
                            if ($item->gambar_file && str_contains($item->gambar_file, 'pengumpulan/')) {
                                return true;
                            }
                            if ($item->nama_file && str_contains($item->nama_file, 'pengumpulan/')) {
                                return true;
                            }
                            if (!$item->gambar_file && !$item->nama_file && $item->link_tugas) {
                                return $item->created_at->diffInSeconds($tugas->created_at) >= 15;
                            }
                            return false;
                        });
                    @endphp

                    @if($pengumpulanStaff->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($pengumpulanStaff as $lampiranSubmit)
                                @if($lampiranSubmit->gambar_file)
                                    <div class="border border-gray-100 rounded-xl overflow-hidden p-2 bg-gray-50 flex flex-col space-y-2">
                                        <img src="{{ asset('storage/' . $lampiranSubmit->gambar_file) }}" class="w-full h-32 object-cover rounded-lg" alt="Gambar Hasil Kerja Staff">
                                        <a href="{{ asset('storage/' . $lampiranSubmit->gambar_file) }}" target="_blank" class="text-xs font-medium text-[#3B28CC] hover:underline px-1 flex items-center gap-1">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Lihat Hasil Gambar
                                        </a>
                                    </div>
                                @endif

                                @if($lampiranSubmit->nama_file)
                                    <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-[#3B28CC] flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-file-arrow-up text-base"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-medium text-gray-800 truncate">{{ basename($lampiranSubmit->nama_file) }}</p>
                                                <p class="text-[10px] text-gray-400 uppercase font-medium">{{ pathinfo($lampiranSubmit->nama_file, PATHINFO_EXTENSION) }} File</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $lampiranSubmit->nama_file) }}" download class="w-8 h-8 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors shrink-0">
                                            <i class="fa-solid fa-download text-xs"></i>
                                        </a>
                                    </div>
                                @endif

                                @if($lampiranSubmit->link_tugas)
                                    <div class="border border-gray-100 p-4 rounded-xl bg-gray-50 flex items-center justify-between sm:col-span-2">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-link text-sm"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-medium text-gray-800">Tautan Hasil Kerja / Workspace</p>
                                                <p class="text-[10px] text-gray-400 truncate max-w-md">{{ $lampiranSubmit->link_tugas }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ $lampiranSubmit->link_tugas }}" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-[#3B28CC] bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors gap-1 shrink-0">
                                            Buka Tautan <i class="fa-solid fa-external-link text-[10px]"></i>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400 italic">Data berkas terdeteksi dikumpulkan tanpa file fisik lampiran.</p>
                    @endif
                </div>
            @endif

            @if($tugas->status_tugas === 'Belum Dikerjakan' || $tugas->status_tugas === 'Revisi')
                <hr class="border-gray-100">
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Formulir Lampiran Hasil Kerja</h4>
                    
                    <form id="form-submit-tugas" action="{{ route('tugas.submit', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Unggah Gambar (Opsional)</label>
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
                                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-gambar_file"></p>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Unggah Dokumen (Opsional)</label>
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
                                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-nama_file"></p>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Tautan Kerja / Workspace Link (Opsional)</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-link text-sm"></i>
                                </div>
                                <input type="url" name="link_tugas" id="link_tugas" value="{{ old('link_tugas') }}" class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all placeholder-gray-400" placeholder="https://example.com/your-work-link">
                            </div>
                            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-link_tugas"></p>
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors gap-2">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                {{ $tugas->status_tugas === 'Revisi' ? 'Kirim Revisi Tugas Sekarang' : 'Kirim Tugas Sekarang' }}
                            </button>
                        </div>
                    </form>
                </div>
            @elseif($tugas->status_tugas === 'Menunggu Persetujuan')
                <hr class="border-gray-100">
                <div class="flex items-center justify-end">
                    <button type="button" disabled class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl cursor-not-allowed gap-2 w-full sm:w-auto">
                        <i class="fa-solid fa-hourglass-half text-xs"></i>
                        Menunggu Peninjauan Manajer
                    </button>
                </div>
            @else
                <hr class="border-gray-100">
                <div class="flex items-center justify-end">
                    <button type="button" disabled class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-green-600 bg-green-50 rounded-xl cursor-not-allowed gap-2 w-full sm:w-auto">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        Tugas Selesai / Disetujui
                    </button>
                </div>
            @endif

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

    document.addEventListener('DOMContentLoaded', () => {
        initRealTimeValidation('form-submit-tugas');
    });
</script>
@endsection
