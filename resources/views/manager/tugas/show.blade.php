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
            <a href="{{ route('tugas.edit', $tugas->id) }}" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Edit Tugas
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 shadow-xs space-y-6">
            <div class="space-y-2">
                <h2 class="text-xl font-bold text-gray-900">{{ $tugas->nama_tugas }}</h2>
                <div class="flex flex-wrap gap-2">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $tugas->kategoritugas === 'Kelompok' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' }}">
                        {{ $tugas->kategoritugas }}
                    </span>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $tugas->prioritas === 'Tinggi' ? 'bg-red-50 text-red-600' : ($tugas->prioritas === 'Sedang' ? 'bg-orange-50 text-orange-600' : 'bg-green-50 text-green-600') }}">
                        Prioritas {{ $tugas->prioritas }}
                    </span>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $tugas->status_tugas === 'Selesai' ? 'bg-green-50 text-green-600' : ($tugas->status_tugas === 'Menunggu Persetujuan' ? 'bg-blue-50 text-blue-600' : ($tugas->status_tugas === 'Revisi' ? 'bg-rose-50 text-rose-600' : 'bg-gray-100 text-gray-600')) }}">
                        {{ $tugas->status_tugas ?? 'Belum Dikerjakan' }}
                    </span>
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="space-y-2">
                <h3 class="text-sm font-bold text-gray-800">Deskripsi Tugas</h3>
                <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $tugas->deskripsi }}</p>
            </div>

            @if($tugas->catatan_revisi)
                <div class="p-4 bg-amber-50/60 border border-amber-200 rounded-xl space-y-1">
                    <h4 class="text-sm font-bold text-amber-800 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i> Catatan Revisi Aktif
                    </h4>
                    <p class="text-sm text-amber-700 whitespace-pre-line">{{ $tugas->catatan_revisi }}</p>
                </div>
            @endif

            <hr class="border-gray-100">

            <div class="space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                    BERKAS PENDUKUNG DARI MANAJER:
                </h3>

                @php
                    $lampiranManager = $tugas->lampirans->filter(function($item) use ($tugas) {
                        if (isset($item->user_id)) {
                            return $item->user_id == $tugas->user_id;
                        }
                        return !str_contains($item->gambar_file, 'pengumpulan') && !str_contains($item->nama_file, 'pengumpulan');
                    });
                @endphp

                @if($lampiranManager->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($lampiranManager as $lm)
                            @if($lm->gambar_file)
                                <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-2xs space-y-3">
                                    <div class="w-full aspect-video rounded-xl overflow-hidden bg-gray-50 border border-gray-100">
                                        <img src="{{ asset('storage/' . $lm->gambar_file) }}" class="w-full h-full object-cover">
                                    </div>
                                    <a href="{{ asset('storage/' . $lm->gambar_file) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#3B28CC] hover:underline">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Lihat Gambar Penuh
                                    </a>
                                </div>
                            @endif

                            @if($lm->nama_file)
                                <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-5 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-file-lines text-[#3B28CC] text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 truncate">{{ basename($lm->nama_file) }}</p>
                                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">{{ pathinfo($lm->nama_file, PATHINFO_EXTENSION) }} FILE</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $lm->nama_file) }}" target="_blank" class="w-9 h-9 border border-gray-200 hover:bg-gray-50 rounded-xl flex items-center justify-center text-gray-500 transition-colors shrink-0">
                                        <i class="fa-solid fa-download text-sm"></i>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">Tidak ada berkas pendukung dari manajer.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-gray-800">Informasi Batas Waktu</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fa-regular fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Tanggal Mulai</p>
                            <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($tugas->tanggal_tugas)->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fa-regular fa-calendar-times"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Batas Deadline</p>
                            <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($tugas->deadline_tugas)->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Departemen</p>
                            <p class="font-semibold text-gray-800">{{ $tugas->departemen->nama_departemen ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs space-y-4">
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-[#3B28CC]"></i> Berkas Pengumpulan Hasil Kerja Anda
                </h3>

                @php
                    $pengumpulanStaff = $tugas->lampirans->filter(function($item) use ($tugas) {
                        if (isset($item->user_id)) {
                            return $item->user_id != $tugas->user_id;
                        }
                        return str_contains($item->gambar_file, 'pengumpulan') || str_contains($item->nama_file, 'pengumpulan');
                    })->first();

                    $hasPengumpulan = $pengumpulanStaff && ($pengumpulanStaff->gambar_file || $pengumpulanStaff->nama_file || $pengumpulanStaff->link_tugas);
                @endphp

                @if($hasPengumpulan)
                    <div class="space-y-4">
                        @if($pengumpulanStaff->gambar_file)
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lampiran Foto</p>
                                <div class="group relative border border-gray-200 rounded-xl overflow-hidden bg-gray-50 aspect-video flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $pengumpulanStaff->gambar_file) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                                        <a href="{{ asset('storage/' . $pengumpulanStaff->gambar_file) }}" target="_blank" class="bg-white text-gray-800 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1 hover:bg-gray-100">
                                            <i class="fa-solid fa-eye"></i> Lihat Foto
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($pengumpulanStaff->nama_file)
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lampiran Dokumen</p>
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-xl bg-gray-50/50">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        <i class="fa-regular fa-file-lines text-blue-500 text-lg shrink-0"></i>
                                        <p class="text-xs font-medium text-gray-700 truncate">{{ basename($pengumpulanStaff->nama_file) }}</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $pengumpulanStaff->nama_file) }}" target="_blank" class="p-1.5 text-[#3B28CC] hover:bg-[#3B28CC]/5 rounded-lg transition-colors ml-2 shrink-0">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($pengumpulanStaff->link_tugas)
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Link Pengumpulan</p>
                                <a href="{{ $pengumpulanStaff->link_tugas }}" target="_blank" class="flex items-center gap-2 p-3 border border-blue-100 rounded-xl bg-blue-50/30 text-xs font-semibold text-[#3B28CC] hover:underline break-all">
                                    <i class="fa-solid fa-link shrink-0"></i>
                                    <span class="truncate">{{ $pengumpulanStaff->link_tugas }}</span>
                                </a>
                            </div>
                        @endif

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
                                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-green-700 transition-colors flex items-center justify-center gap-1.5 shadow-xs disabled:bg-gray-400 disabled:cursor-not-allowed">
                                            <i class="fa-regular fa-circle-check text-sm"></i> Setujui Tugas
                                        </button>
                                    </form>
                                    <button type="button" id="btn_buka_revisi" class="flex-1 bg-amber-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-amber-600 transition-colors flex items-center justify-center gap-1.5 shadow-xs disabled:bg-gray-400 disabled:cursor-not-allowed">
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
                                    </div>
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" id="btn_batal_revisi" class="px-3 py-1.5 border border-gray-200 text-gray-600 bg-white rounded-lg text-xs font-semibold hover:bg-gray-50 transition-colors disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">Batal</button>
                                        <button type="submit" class="px-3 py-1.5 bg-amber-500 text-white rounded-lg text-xs font-semibold hover:bg-amber-600 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">Kirim Revisi</button>
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
        </div>
    </div>
</div>

<script>
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
</script>
@endsection
