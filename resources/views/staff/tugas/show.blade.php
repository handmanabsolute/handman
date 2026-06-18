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
                    @php $isOverdue = \Carbon\Carbon::parse($tugas->deadline_tugas)->isPast() && $tugas->status_tugas !== 'Selesai'; @endphp
                    <span class="{{ $isOverdue ? 'text-red-600' : 'text-gray-800' }} font-semibold block mt-1.5">{{ \Carbon\Carbon::parse($tugas->deadline_tugas)->format('d F Y, H:i') }}</span>
                    @if($isOverdue)
                        <span class="text-[9px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-md mt-0.5 inline-block">Melewati Deadline</span>
                    @endif
                </div>
                <div>
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Status Saat Ini</span>
                    <div class="mt-1.5">
                        @if($tugas->status_tugas == 'Selesai')
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-green-50 text-green-700 rounded-lg border border-green-100 whitespace-nowrap">Selesai</span>
                        @elseif($tugas->status_tugas == 'Menunggu Persetujuan')
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 rounded-lg border border-blue-100 whitespace-nowrap">Menunggu Persetujuan</span>
                        @elseif($tugas->status_tugas == 'Revisi')
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-rose-50 text-rose-700 rounded-lg border border-rose-100 whitespace-nowrap">Revisi</span>
                        @else
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-gray-50 text-gray-700 rounded-lg border border-gray-200 whitespace-nowrap">Belum Dikerjakan</span>
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
                                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-indigo-50 text-[#3B28CC] rounded-xl flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-image text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ asset('storage/' . $lampiran->gambar_file) }}" target="_blank" class="text-xs font-semibold text-gray-800 hover:text-[#3B28CC] hover:underline truncate block">
                                                {{ basename($lampiran->gambar_file) }}
                                            </a>
                                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Gambar</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($lampiran->nama_file)
                                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-indigo-50 text-[#3B28CC] rounded-xl flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-file-lines text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ asset('storage/' . $lampiran->nama_file) }}" target="_blank" class="text-xs font-semibold text-gray-800 hover:text-[#3B28CC] hover:underline truncate block">
                                                {{ basename($lampiran->nama_file) }}
                                            </a>
                                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">{{ pathinfo($lampiran->nama_file, PATHINFO_EXTENSION) }} FILE</p>
                                        </div>
                                    </div>
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
                                    <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 bg-indigo-50 text-[#3B28CC] rounded-xl flex items-center justify-center shrink-0">
                                                <i class="fa-regular fa-image text-lg"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ asset('storage/' . $lampiranSubmit->gambar_file) }}" target="_blank" class="text-xs font-semibold text-gray-800 hover:text-[#3B28CC] hover:underline truncate block">
                                                    {{ basename($lampiranSubmit->gambar_file) }}
                                                </a>
                                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Gambar</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($lampiranSubmit->nama_file)
                                    <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 bg-indigo-50 text-[#3B28CC] rounded-xl flex items-center justify-center shrink-0">
                                                <i class="fa-regular fa-file-lines text-lg"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ asset('storage/' . $lampiranSubmit->nama_file) }}" target="_blank" class="text-xs font-semibold text-gray-800 hover:text-[#3B28CC] hover:underline truncate block">
                                                    {{ basename($lampiranSubmit->nama_file) }}
                                                </a>
                                                <p class="text-[10px] text-gray-400 uppercase font-medium">{{ pathinfo($lampiranSubmit->nama_file, PATHINFO_EXTENSION) }} File</p>
                                            </div>
                                        </div>
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

                        @if($tugas->status_tugas === 'Menunggu Persetujuan')
                            <div class="flex justify-end pt-2">
                                <button type="button" id="btn-edit-pengumpulan"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-[#3B28CC] bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition-colors cursor-pointer">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    Edit Pengumpulan
                                </button>
                            </div>
                        @endif
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

                        @php
                            $pengumpulanStaffLocal = $tugas->lampirans->filter(function($item) use ($tugas) {
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
                            $existingGambar = $pengumpulanStaffLocal->first()->gambar_file ?? '';
                            $existingDokumen = $pengumpulanStaffLocal->first()->nama_file ?? '';
                            $existingLink = $pengumpulanStaffLocal->first()->link_tugas ?? '';
                        @endphp
                        <input type="hidden" name="existing_gambar" id="existing_gambar" value="{{ $existingGambar }}">
                        <input type="hidden" name="existing_dokumen" id="existing_dokumen" value="{{ $existingDokumen }}">

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
                                <label class="block text-xs font-semibold text-gray-700">Unggah Gambar</label>
                                <div class="relative group border-2 border-dashed border-gray-200 hover:border-[#3B28CC] rounded-xl p-6 bg-gray-50/50 hover:bg-gray-50 transition-all text-center cursor-pointer flex flex-col items-center justify-center min-h-40">
                                    <input type="file" name="gambar_file" id="gambar_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div id="container_preview_gambar" class="hidden absolute inset-0 bg-white rounded-xl p-4 z-20 space-y-2 justify-center flex flex-col">
                                        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Preview Gambar</p>
                                            <button type="button" id="hapus_gambar" class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-xs hover:bg-red-600 transition-colors">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-600 p-2 bg-gray-50 rounded-lg text-left">
                                            <i class="fa-regular fa-image text-[#3B28CC]"></i>
                                            <a id="link_preview_gambar" href="#" target="_blank" class="font-medium text-[#3B28CC] hover:underline truncate max-w-[85%]" title="Klik untuk preview gambar"></a>
                                        </div>
                                    </div>
                                    <div id="placeholder_gambar" class="flex flex-col items-center justify-center space-y-2">
                                        <div class="w-10 h-10 bg-[#3B28CC] text-white flex items-center justify-center rounded-xl shadow-sm">
                                            <i class="fa-regular fa-image text-lg"></i>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Upload Gambar</p>
                                        <p class="text-xs text-gray-400">JPG, PNG, WebP (Max 10MB)</p>
                                    </div>
                                </div>
                                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-gambar_file"></p>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-gray-700">Unggah Dokumen</label>
                                <div class="relative group border-2 border-dashed border-gray-200 hover:border-[#3B28CC] rounded-xl p-6 bg-gray-50/50 hover:bg-gray-50 transition-all text-center cursor-pointer flex flex-col items-center justify-center min-h-40">
                                    <input type="file" name="nama_file" id="nama_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div id="container_preview_dokumen" class="hidden absolute inset-0 bg-white rounded-xl p-4 z-20 space-y-2 justify-center flex flex-col">
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
                                        <p class="text-xs text-gray-400">PDF, DOCX, XLSX, PPTX, TXT (Max 20MB)</p>
                                    </div>
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
                                <input type="url" name="link_tugas" id="link_tugas" value="{{ old('link_tugas', $existingLink) }}" class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all placeholder-gray-400" placeholder="https://example.com/your-work-link">
                            </div>
                            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-link_tugas"></p>
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors gap-2 cursor-pointer">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                {{ $tugas->status_tugas === 'Revisi' ? 'Kirim Revisi Tugas Sekarang' : 'Kirim Tugas Sekarang' }}
                            </button>
                        </div>
                    </form>
                </div>
            @elseif($tugas->status_tugas === 'Menunggu Persetujuan')
                <div id="form-edit-pengumpulan" class="hidden space-y-4">
                    <hr class="border-gray-100">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Edit Lampiran Hasil Kerja</h4>
                        <button type="button" id="btn-batal-edit" class="text-xs text-gray-500 hover:text-gray-700 font-semibold cursor-pointer">Batal</button>
                    </div>
                    <form id="form-submit-tugas" action="{{ route('tugas.submit', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @php
                            $pengumpulanStaffEdit = $tugas->lampirans->filter(function($item) use ($tugas) {
                                if ($item->gambar_file && str_contains($item->gambar_file, 'pengumpulan/')) return true;
                                if ($item->nama_file && str_contains($item->nama_file, 'pengumpulan/')) return true;
                                if (!$item->gambar_file && !$item->nama_file && $item->link_tugas) return $item->created_at->diffInSeconds($tugas->created_at) >= 15;
                                return false;
                            });
                            $editGambar = $pengumpulanStaffEdit->first()->gambar_file ?? '';
                            $editDokumen = $pengumpulanStaffEdit->first()->nama_file ?? '';
                            $editLink = $pengumpulanStaffEdit->first()->link_tugas ?? '';
                        @endphp
                        <input type="hidden" name="existing_gambar" id="existing_gambar" value="{{ $editGambar }}">
                        <input type="hidden" name="existing_dokumen" id="existing_dokumen" value="{{ $editDokumen }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-gray-700">Unggah Gambar</label>
                                <div class="relative group border-2 border-dashed border-gray-200 hover:border-[#3B28CC] rounded-xl p-6 bg-gray-50/50 hover:bg-gray-50 transition-all text-center cursor-pointer flex flex-col items-center justify-center min-h-40">
                                    <input type="file" name="gambar_file" id="gambar_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div id="container_preview_gambar" class="hidden absolute inset-0 bg-white rounded-xl p-4 z-20 space-y-2 justify-center flex flex-col">
                                        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Preview Gambar</p>
                                            <button type="button" id="hapus_gambar" class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-xs hover:bg-red-600 transition-colors"><i class="fa-solid fa-xmark text-xs"></i></button>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-600 p-2 bg-gray-50 rounded-lg text-left">
                                            <i class="fa-regular fa-image text-[#3B28CC]"></i>
                                            <a id="link_preview_gambar" href="#" target="_blank" class="font-medium text-[#3B28CC] hover:underline truncate max-w-[85%]"></a>
                                        </div>
                                    </div>
                                    <div id="placeholder_gambar" class="flex flex-col items-center justify-center space-y-2">
                                        <div class="w-10 h-10 bg-[#3B28CC] text-white flex items-center justify-center rounded-xl shadow-sm"><i class="fa-regular fa-image text-lg"></i></div>
                                        <p class="text-sm font-bold text-gray-800">Upload Gambar</p>
                                        <p class="text-xs text-gray-400">JPG, PNG, WebP (Max 10MB)</p>
                                    </div>
                                </div>
                                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-gambar_file"></p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-gray-700">Unggah Dokumen</label>
                                <div class="relative group border-2 border-dashed border-gray-200 hover:border-[#3B28CC] rounded-xl p-6 bg-gray-50/50 hover:bg-gray-50 transition-all text-center cursor-pointer flex flex-col items-center justify-center min-h-40">
                                    <input type="file" name="nama_file" id="nama_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div id="container_preview_dokumen" class="hidden absolute inset-0 bg-white rounded-xl p-4 z-20 space-y-2 justify-center flex flex-col">
                                        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Preview Dokumen</p>
                                            <button type="button" id="hapus_dokumen" class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-xs hover:bg-red-600 transition-colors"><i class="fa-solid fa-xmark text-xs"></i></button>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-600 p-2 bg-gray-50 rounded-lg text-left">
                                            <i class="fa-regular fa-file text-[#3B28CC]"></i>
                                            <a id="link_preview_dokumen" href="#" target="_blank" class="font-medium text-[#3B28CC] hover:underline truncate max-w-[85%]"></a>
                                        </div>
                                    </div>
                                    <div id="placeholder_dokumen" class="flex flex-col items-center justify-center space-y-2">
                                        <div class="w-10 h-10 bg-blue-100 text-[#3B28CC] flex items-center justify-center rounded-xl"><i class="fa-regular fa-file-lines text-lg"></i></div>
                                        <p class="text-sm font-bold text-gray-800">Upload Dokumen</p>
                                        <p class="text-xs text-gray-400">PDF, DOCX, XLSX, PPTX, TXT (Max 20MB)</p>
                                    </div>
                                </div>
                                <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-nama_file"></p>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Tautan Kerja / Workspace Link (Opsional)</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-link text-sm"></i></div>
                                <input type="url" name="link_tugas" id="link_tugas" value="{{ old('link_tugas', $editLink) }}" class="block w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all placeholder-gray-400" placeholder="https://example.com/your-work-link">
                            </div>
                            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-link_tugas"></p>
                        </div>
                        <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors gap-2 cursor-pointer">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                Perbarui Pengumpulan
                            </button>
                        </div>
                    </form>
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

<x-confirm-modal id="modal-validation-warning" title="Pengisian Berkas Kurang" message="Minimal harus mengunggah satu berkas (gambar/dokumen) atau mengisi tautan link tugas." action="closeModal('modal-validation-warning'); // executeGlobalAjaxSubmit" type="amber" />

<script>
    (function() {
        const btnEdit = document.getElementById('btn-edit-pengumpulan');
        const btnBatalEdit = document.getElementById('btn-batal-edit');
        const formEdit = document.getElementById('form-edit-pengumpulan');
        if (btnEdit && formEdit && btnBatalEdit) {
            btnEdit.addEventListener('click', function() {
                btnEdit.closest('.flex.justify-end').classList.add('hidden');
                formEdit.classList.remove('hidden');
                formEdit.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
            btnBatalEdit.addEventListener('click', function() {
                formEdit.classList.add('hidden');
                btnEdit.closest('.flex.justify-end').classList.remove('hidden');
            });
        }
    })();

    function initFormSubmit() {
        let gambarObjectUrl = null;
        let dokumenObjectUrl = null;
        const MAX_GAMBAR_SIZE = 10 * 1024 * 1024;
        const MAX_DOKUMEN_SIZE = 20 * 1024 * 1024;
        let gambarValid = true;
        let dokumenValid = true;

        const formSubmitTugas = document.getElementById('form-submit-tugas');
        if (!formSubmitTugas) return;

        const btnSubmit = formSubmitTugas.querySelector('button[type="submit"]');
        const gambarFile = document.getElementById('gambar_file');
        const hapusGambar = document.getElementById('hapus_gambar');
        const namaFile = document.getElementById('nama_file');
        const hapusDokumen = document.getElementById('hapus_dokumen');

        const existingGambarInput = document.getElementById('existing_gambar');
        const existingDokumenInput = document.getElementById('existing_dokumen');

        if (existingGambarInput && existingGambarInput.value) {
            const previewContainer = document.getElementById('container_preview_gambar');
            const linkElement = document.getElementById('link_preview_gambar');
            if (previewContainer && linkElement) {
                const path = existingGambarInput.value;
                linkElement.setAttribute('href', '/storage/' + path);
                linkElement.textContent = path.split('/').pop() + ' (File Sebelumnya)';
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('flex');
            }
        }

        if (existingDokumenInput && existingDokumenInput.value) {
            const previewContainer = document.getElementById('container_preview_dokumen');
            const linkElement = document.getElementById('link_preview_dokumen');
            if (previewContainer && linkElement) {
                const path = existingDokumenInput.value;
                linkElement.setAttribute('href', '/storage/' + path);
                linkElement.textContent = path.split('/').pop() + ' (File Sebelumnya)';
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('flex');
            }
        }

        function updateSubmitButtonState() {
            if (gambarValid && dokumenValid) {
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            } else {
                if (btnSubmit) {
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        if (gambarFile) {
            gambarFile.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (gambarObjectUrl) {
                    URL.revokeObjectURL(gambarObjectUrl);
                    gambarObjectUrl = null;
                }

                const errorEl = document.getElementById('error-gambar_file');
                if (file) {
                    if (file.size > MAX_GAMBAR_SIZE) {
                        gambarValid = false;
                        if (errorEl) {
                            errorEl.textContent = "Ukuran file Gambar melebihi batas maksimum 10MB!";
                            errorEl.classList.remove('hidden');
                        }
                    } else {
                        gambarValid = true;
                        if (errorEl) {
                            errorEl.classList.add('hidden');
                            errorEl.textContent = "";
                        }
                    }
                    gambarObjectUrl = URL.createObjectURL(file);

                    const linkElement = document.getElementById('link_preview_gambar');
                    if (linkElement) {
                        linkElement.setAttribute('href', gambarObjectUrl);
                        linkElement.textContent = file.name + ' (Klik untuk preview)';
                    }

                    const previewContainer = document.getElementById('container_preview_gambar');
                    if (previewContainer) {
                        previewContainer.classList.remove('hidden');
                        previewContainer.classList.add('flex');
                    }
                } else {
                    gambarValid = true;
                    if (errorEl) {
                        errorEl.classList.add('hidden');
                    }
                }
                updateSubmitButtonState();
            });
        }

        if (hapusGambar) {
            hapusGambar.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (gambarFile) {
                    gambarFile.value = '';
                }
                const existingGambarInput = document.getElementById('existing_gambar');
                if (existingGambarInput) {
                    existingGambarInput.value = '';
                }
                const previewContainer = document.getElementById('container_preview_gambar');
                if (previewContainer) {
                    previewContainer.classList.remove('flex');
                    previewContainer.classList.add('hidden');
                }
                if (gambarObjectUrl) {
                    URL.revokeObjectURL(gambarObjectUrl);
                    gambarObjectUrl = null;
                }
                gambarValid = true;
                const errorEl = document.getElementById('error-gambar_file');
                if (errorEl) {
                    errorEl.classList.add('hidden');
                }
                updateSubmitButtonState();
            });
        }

        if (namaFile) {
            namaFile.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (dokumenObjectUrl) {
                    URL.revokeObjectURL(dokumenObjectUrl);
                    dokumenObjectUrl = null;
                }

                const errorEl = document.getElementById('error-nama_file');
                if (file) {
                    if (file.size > MAX_DOKUMEN_SIZE) {
                        dokumenValid = false;
                        if (errorEl) {
                            errorEl.textContent = "Ukuran file Dokumen melebihi batas maksimum 20MB!";
                            errorEl.classList.remove('hidden');
                        }
                    } else {
                        dokumenValid = true;
                        if (errorEl) {
                            errorEl.classList.add('hidden');
                            errorEl.textContent = "";
                        }
                    }
                    dokumenObjectUrl = URL.createObjectURL(file);

                    const linkElement = document.getElementById('link_preview_dokumen');
                    if (linkElement) {
                        linkElement.setAttribute('href', dokumenObjectUrl);
                        linkElement.textContent = file.name + ' (Klik untuk preview)';
                    }

                    const previewContainer = document.getElementById('container_preview_dokumen');
                    if (previewContainer) {
                        previewContainer.classList.remove('hidden');
                        previewContainer.classList.add('flex');
                    }
                } else {
                    dokumenValid = true;
                    if (errorEl) {
                        errorEl.classList.add('hidden');
                    }
                }
                updateSubmitButtonState();
            });
        }

        if (hapusDokumen) {
            hapusDokumen.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (namaFile) {
                    namaFile.value = '';
                }
                const existingDokumenInput = document.getElementById('existing_dokumen');
                if (existingDokumenInput) {
                    existingDokumenInput.value = '';
                }
                const previewContainer = document.getElementById('container_preview_dokumen');
                if (previewContainer) {
                    previewContainer.classList.remove('flex');
                    previewContainer.classList.add('hidden');
                }
                if (dokumenObjectUrl) {
                    URL.revokeObjectURL(dokumenObjectUrl);
                    dokumenObjectUrl = null;
                }
                dokumenValid = true;
                const errorEl = document.getElementById('error-nama_file');
                if (errorEl) {
                    errorEl.classList.add('hidden');
                }
                updateSubmitButtonState();
            });
        }

        // Clear minimum upload validation error when any input has value
        function clearMinUploadError() {
            const errorEl = document.getElementById('error-gambar_file');
            if (errorEl && errorEl.textContent.includes('Minimal')) {
                errorEl.classList.add('hidden');
                errorEl.textContent = '';
            }
        }

        if (gambarFile) {
            gambarFile.addEventListener('change', clearMinUploadError);
        }
        if (namaFile) {
            namaFile.addEventListener('change', clearMinUploadError);
        }
        const linkTugasInput = document.getElementById('link_tugas');
        if (linkTugasInput) {
            linkTugasInput.addEventListener('input', clearMinUploadError);
        }

        formSubmitTugas.addEventListener('submit', function(e) {
            const hasGambar = (gambarFile && gambarFile.files.length > 0) || (existingGambarInput && existingGambarInput.value !== '');
            const hasDokumen = (namaFile && namaFile.files.length > 0) || (existingDokumenInput && existingDokumenInput.value !== '');
            const linkInput = document.getElementById('link_tugas');
            const hasLink = linkInput && linkInput.value.trim() !== '';

            if (!hasGambar && !hasDokumen && !hasLink) {
                e.preventDefault();
                e.stopPropagation();
                const errorEl = document.getElementById('error-gambar_file');
                if (errorEl) {
                    errorEl.textContent = "Minimal harus mengunggah satu berkas (gambar/dokumen) atau mengisi tautan link tugas.";
                    errorEl.classList.remove('hidden');
                }
                openModal('modal-validation-warning');
            }
        });

        initRealTimeValidation('form-submit-tugas');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFormSubmit);
    } else {
        initFormSubmit();
    }
</script>
@endsection
