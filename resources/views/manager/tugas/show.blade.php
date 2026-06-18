@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('content')
<div class="mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Tugas</h1>
            <p class="text-sm text-gray-500">Melihat rincian informasi, batas waktu, dan lampiran pengerjaan tugas.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tugas.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('tugas.edit', $tugas->id) }}" class="bg-indigo-50/50 text-[#3B28CC] border border-indigo-100 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-50 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if($tugas->catatan_revisi)
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700 shrink-0 mt-0.5">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-amber-800">Catatan Revisi :</h4>
                <p class="text-xs text-amber-700 mt-0.5 leading-relaxed">{{ $tugas->catatan_revisi }}</p>
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

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6 bg-gray-50/50 p-4 rounded-xl border border-gray-100/50 text-sm">
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
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Penanggung Jawab</span>
                    <span class="text-[#3B28CC] font-semibold block mt-1.5">{{ $tugas->detailTugas->user->nama_lengkap }}</span>
                </div>
                @elseif($tugas->kategoritugas === 'Kelompok' && $tugas->detailTugas && $tugas->detailTugas->grupKerja)
                <div>
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Penanggung Jawab</span>
                    <span class="text-[#3B28CC] font-semibold block mt-1.5">{{ $tugas->detailTugas->grupKerja->nama_grup }}</span>
                </div>
                @endif
                <div>
                    <span class="block font-semibold text-gray-400 text-xs uppercase tracking-wider">Departemen</span>
                    <span class="text-gray-800 font-semibold block mt-1.5">{{ $tugas->departemen->nama_departemen ?? '-' }}</span>
                </div>
            </div>
        </div>


            <div class="space-y-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Deskripsi Tugas</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line bg-gray-50 p-4 rounded-xl border border-gray-100 leading-relaxed">
                    {{ $tugas->deskripsi }}
                </p>
            </div>

            <hr class="border-gray-100">


            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Berkas Pendukung dari Manager</h4>

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
                        @foreach($lampiranManager as $lm)
                            @if($lm->gambar_file)
                                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-indigo-50 text-[#3B28CC] rounded-xl flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-image text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ asset('storage/' . $lm->gambar_file) }}" target="_blank" class="text-xs font-semibold text-gray-800 hover:text-[#3B28CC] hover:underline truncate block">
                                                {{ basename($lm->gambar_file) }}
                                            </a>
                                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Gambar</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($lm->nama_file)
                                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-indigo-50 text-[#3B28CC] rounded-xl flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-file-lines text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ asset('storage/' . $lm->nama_file) }}" target="_blank" class="text-xs font-semibold text-gray-800 hover:text-[#3B28CC] hover:underline truncate block">
                                                {{ basename($lm->nama_file) }}
                                            </a>
                                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">{{ pathinfo($lm->nama_file, PATHINFO_EXTENSION) }} FILE</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($lm->link_tugas)
                                <div class="border border-gray-100 p-4 rounded-xl bg-gray-50 flex items-center justify-between sm:col-span-2">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-link text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-medium text-gray-800">Tautan Eksternal / Workspace</p>
                                            <p class="text-[10px] text-gray-400 truncate max-w-md">{{ $lm->link_tugas }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $lm->link_tugas }}" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-[#3B28CC] bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors gap-1 shrink-0">
                                        Buka Link <i class="fa-solid fa-external-link text-[10px]"></i>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">Tidak ada berkas pendukung dari manager.</p>
                @endif
            </div>

            @if($tugas->status_tugas !== 'Belum Dikerjakan')
                <hr class="border-gray-100">


                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Berkas Pengumpulan Hasil Kerja Staff</h4>

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
                        })->first();

                        $hasPengumpulan = $pengumpulanStaff && ($pengumpulanStaff->gambar_file || $pengumpulanStaff->nama_file || $pengumpulanStaff->link_tugas);
                    @endphp

                    @if($hasPengumpulan)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if($pengumpulanStaff->gambar_file)
                                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-indigo-50 text-[#3B28CC] rounded-xl flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-image text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ asset('storage/' . $pengumpulanStaff->gambar_file) }}" target="_blank" class="text-xs font-semibold text-gray-800 hover:text-[#3B28CC] hover:underline truncate block">
                                                {{ basename($pengumpulanStaff->gambar_file) }}
                                            </a>
                                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Gambar</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($pengumpulanStaff->nama_file)
                                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-indigo-50 text-[#3B28CC] rounded-xl flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-file-lines text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ asset('storage/' . $pengumpulanStaff->nama_file) }}" target="_blank" class="text-xs font-semibold text-gray-800 hover:text-[#3B28CC] hover:underline truncate block">
                                                {{ basename($pengumpulanStaff->nama_file) }}
                                            </a>
                                            <p class="text-[10px] text-gray-400 uppercase font-medium">{{ pathinfo($pengumpulanStaff->nama_file, PATHINFO_EXTENSION) }} File</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($pengumpulanStaff->link_tugas)
                                <div class="border border-gray-100 p-4 rounded-xl bg-gray-50 flex items-center justify-between sm:col-span-2">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-link text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-medium text-gray-800">Tautan Hasil Kerja / Workspace</p>
                                            <p class="text-[10px] text-gray-400 truncate max-w-md">{{ $pengumpulanStaff->link_tugas }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $pengumpulanStaff->link_tugas }}" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-[#3B28CC] bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors gap-1 shrink-0">
                                        Buka Tautan <i class="fa-solid fa-external-link text-[10px]"></i>
                                    </a>
                                </div>
                            @endif
                        </div>

                            <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                                <span>Diserahkan pada:</span>
                                <span class="font-medium text-gray-600">{{ \Carbon\Carbon::parse($pengumpulanStaff->created_at)->format('d M Y, H:i') }}</span>
                            </div>

                            <hr class="border-gray-100">


                            <div class="space-y-3">
                                @if($tugas->status_tugas === 'Menunggu Persetujuan')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('tugas.review', $tugas->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="setujui">
                                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-green-700 transition-colors flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                                                <i class="fa-regular fa-circle-check text-sm"></i> Setujui Tugas
                                            </button>
                                        </form>
                                        <button type="button" id="btn_buka_revisi" class="flex-1 bg-amber-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-amber-600 transition-colors flex items-center justify-center gap-1.5 shadow-xs">
                                            <i class="fa-solid fa-arrow-rotate-left"></i> Ajukan Revisi
                                        </button>
                                    </div>

                                    <form id="form_revisi" action="{{ route('tugas.review', $tugas->id) }}" method="POST" class="hidden border border-amber-200 bg-amber-50/30 rounded-xl p-4 space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="revisi">
                                        <div class="space-y-1.5">
                                            <label class="text-xs font-bold text-amber-800">Catatan Revisi</label>
                                            <textarea name="catatan_revisi" rows="3" placeholder="Tulis instruksi perbaikan untuk staff di sini....." class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500" required>{{ old('catatan_revisi', $tugas->catatan_revisi) }}</textarea>
                                            <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-catatan_revisi"></p>
                                        </div>
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" id="btn_batal_revisi" class="px-3 py-1.5 border border-gray-200 text-gray-600 bg-white rounded-lg text-xs font-semibold hover:bg-gray-50 transition-colors">Batal</button>
                                            <button type="submit" class="px-3 py-1.5 bg-amber-500 text-white rounded-lg text-xs font-semibold hover:bg-amber-600 transition-colors">Kirim Revisi</button>
                                        </div>
                                    </form>
                                @elseif($tugas->status_tugas === 'Selesai')
                                    <div class="p-3 bg-green-50 border border-green-200 text-green-700 text-xs font-semibold rounded-xl flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-circle-check text-sm"></i> Tugas ini telah disetujui dan selesai.
                                    </div>
                                @elseif($tugas->status_tugas === 'Revisi')
                                    <div class="p-3 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold rounded-xl flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-arrow-rotate-left text-sm"></i> Tugas dikembalikan ke staff untuk proses revisi.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="py-8 px-4 border-2 border-dashed border-gray-100 rounded-xl text-center space-y-2">
                            <div class="w-10 h-10 bg-gray-50 text-gray-400 flex items-center justify-center rounded-full mx-auto">
                                <i class="fa-solid fa-hourglass-start text-base"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-400">Lampiran belum dikumpulkan</p>
                            <p class="text-xs text-gray-400">Staff penanggung jawab belum mengunggah berkas pengerjaan untuk tugas ini.</p>
                        </div>
                    @endif
                </div>
            @endif

    </div>
</div>

<script>
    (function() {
        const btnBukaRevisi = document.getElementById('btn_buka_revisi');
        const btnBatalRevisi = document.getElementById('btn_batal_revisi');
        const formRevisi = document.getElementById('form_revisi');

        if (btnBukaRevisi && formRevisi && btnBatalRevisi) {
            btnBukaRevisi.addEventListener('click', function() {
                formRevisi.classList.remove('hidden');
                formRevisi.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });

            btnBatalRevisi.addEventListener('click', function() {
                formRevisi.classList.add('hidden');
            });
        }

        if (formRevisi && typeof initRealTimeValidation === 'function') {
            initRealTimeValidation('form_revisi');
        }
    })();
</script>
@endsection
