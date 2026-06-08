@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Instruksi Tugas</h1>
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
                    <span class="block font-medium text-gray-400 text-xs">Prioritas</span>
                    @if($tugas->prioritas == 'Tinggi')
                        <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-medium bg-red-50 text-red-700 rounded-lg">{{ $tugas->prioritas }}</span>
                    @elseif($tugas->prioritas == 'Sedang')
                        <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-medium bg-amber-50 text-amber-700 rounded-lg">{{ $tugas->prioritas }}</span>
                    @else
                        <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-medium bg-green-50 text-green-700 rounded-lg">{{ $tugas->prioritas }}</span>
                    @endif
                </div>
                <div>
                    <span class="block font-medium text-gray-400 text-xs">Tanggal Diberikan</span>
                    <span class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($tugas->tanggal_tugas)->format('d F Y, H:i') }}</span>
                </div>
                <div>
                    <span class="block font-medium text-gray-400 text-xs">Batas Akhir Selesai</span>
                    <span class="text-red-600 font-semibold">{{ \Carbon\Carbon::parse($tugas->deadline_tugas)->format('d F Y, H:i') }}</span>
                </div>
                <div>
                    <span class="block font-medium text-gray-400 text-xs">Status Saat Ini</span>
                    @if($tugas->status_tugas == 'Selesai')
                        <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 text-xs font-medium bg-green-50 text-green-700 rounded-lg">Selesai</span>
                    @elseif($tugas->status_tugas == 'Menunggu Persetujuan')
                        <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 rounded-lg">Menunggu Persetujuan</span>
                    @elseif($tugas->status_tugas == 'Revisi')
                        <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 text-xs font-medium bg-rose-50 text-rose-700 rounded-lg">Revisi</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 text-xs font-medium bg-gray-50 text-gray-700 rounded-lg">Belum Dikerjakan</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-50">
                    <h3 class="font-bold text-gray-800">Deskripsi & Lampiran Acuan</h3>
                </div>

                <div class="p-6 space-y-6">
                    <div class="space-y-1.5">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Instruksi Kerja:</h4>
                        <p class="text-sm text-gray-700 whitespace-pre-line bg-gray-50 p-4 rounded-xl border border-gray-100 leading-relaxed">
                            {{ $tugas->deskripsi }}
                        </p>
                    </div>

                    <div class="space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Berkas Pendukung dari Manajer:</h4>

                        @if($tugas->lampirans && $tugas->lampirans->where('user_id', '!=', Auth::id())->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($tugas->lampirans->where('user_id', '!=', Auth::id()) as $lampiran)
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
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-[#3B28CC] flex items-center justify-center">
                                                    <i class="fa-solid fa-file-lines text-base"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-gray-800">Dokumen Pendukung</p>
                                                    <p class="text-[10px] text-gray-400 uppercase">{{ pathinfo($lampiran->nama_file, PATHINFO_EXTENSION) }} File</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $lampiran->nama_file) }}" download class="w-8 h-8 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors">
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
                                            <a href="{{ $lampiran->link_tugas }}" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-[#3B28CC] bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors gap-1">
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
                </div>
            </div>

            @if($tugas->status_tugas !== 'Belum Dikerjakan')
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-box-archive text-sm text-[#3B28CC]"></i>
                            Berkas Pengumpulan Hasil Kerja Anda
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($tugas->lampirans && $tugas->lampirans->where('user_id', Auth::id())->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($tugas->lampirans->where('user_id', Auth::id()) as $lampiranSubmit)
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
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-[#3B28CC] flex items-center justify-center">
                                                    <i class="fa-solid fa-file-arrow-up text-base"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-gray-800">Dokumen Hasil</p>
                                                    <p class="text-[10px] text-gray-400 uppercase">{{ pathinfo($lampiranSubmit->nama_file, PATHINFO_EXTENSION) }} File</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $lampiranSubmit->nama_file) }}" download class="w-8 h-8 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors">
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
                                            <a href="{{ $lampiranSubmit->link_tugas }}" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-[#3B28CC] bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors gap-1">
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
                </div>
            @endif

            <div class="flex items-center justify-end pt-2">
                @if($tugas->status_tugas === 'Belum Dikerjakan' || $tugas->status_tugas === 'Revisi')
                    <a href="{{ route('staff.tugas.submit.form', $tugas->id) }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                        {{ $tugas->status_tugas === 'Revisi' ? 'Kumpulkan Revisi Tugas' : 'Kumpulkan Tugas Anda' }}
                    </a>
                @elseif($tugas->status_tugas === 'Menunggu Persetujuan')
                    <button type="button" disabled class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl cursor-not-allowed gap-2">
                        <i class="fa-solid fa-hourglass-half text-xs"></i>
                        Menunggu Peninjauan Manajer
                    </button>
                @else
                    <button type="button" disabled class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-green-600 bg-green-50 rounded-xl cursor-not-allowed gap-2">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        Tugas Selesai / Disetujui
                    </button>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
