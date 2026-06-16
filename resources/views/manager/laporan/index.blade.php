@extends('layouts.app')

@section('title', 'Laporan Masalah')

@section('content')
<div class="space-y-6 pb-10">


    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Masalah</h1>
            <p class="text-sm text-gray-500 mt-0.5">Sampaikan laporan divisi atau pengaduan operasional Anda ke Admin.</p>
        </div>
        <button type="button" onclick="openLaporModal()"
                class="w-full sm:w-auto bg-[#3B28CC] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#2c1fa3] transition-colors flex items-center justify-center gap-2 cursor-pointer shadow-xs">
            <i class="fa-solid fa-plus text-xs"></i> Buat Laporan
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-100 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-600 text-base shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 text-sm text-rose-800 bg-rose-50 border border-rose-100 rounded-xl space-y-1">
            <div class="flex items-center gap-2 font-bold">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-base"></i> Terjadi Kesalahan:
            </div>
            <ul class="list-disc pl-5 text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @if($laporans->isEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl p-14 text-center shadow-sm">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center text-[#3B28CC]">
                    <i class="fa-solid fa-clipboard-question text-2xl"></i>
                </div>
                <p class="font-semibold text-gray-500">Belum ada laporan</p>
                <p class="text-xs text-gray-400 max-w-xs">Semua laporan pengaduan yang Anda buat akan tampil di sini beserta tanggapan dari Admin.</p>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($laporans as $laporan)
            <div class="bg-white border border-gray-100 rounded-2xl shadow-xs overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-xs text-gray-400">Dikirim {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-1 font-medium">{{ $laporan->isi }}</p>
                    </div>

                    <div class="flex items-center gap-3 self-end sm:self-center shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-lg border
                            {{ $laporan->status === 'Menunggu'
                                ? 'bg-amber-50 text-amber-700 border-amber-100'
                                : ($laporan->status === 'Dibalas'
                                    ? 'bg-blue-50 text-blue-700 border-blue-100'
                                    : 'bg-green-50 text-green-700 border-green-100') }}">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ $laporan->status === 'Menunggu'
                                    ? 'bg-amber-500'
                                    : ($laporan->status === 'Dibalas'
                                        ? 'bg-blue-500'
                                        : 'bg-green-500') }}"></span>
                            {{ $laporan->status === 'Menunggu' ? 'Belum Dibalas' : $laporan->status }}
                        </span>

                        <a href="{{ route('laporan.show', $laporan->id) }}"
                           class="bg-white border border-gray-200 text-gray-700 hover:text-[#3B28CC] hover:bg-indigo-50/50 px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                            Lihat Detail
                        </a>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>


<div id="modal-lapor" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">

    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-xs" onclick="closeLaporModal()"></div>


    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Kirim Laporan</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Sampaikan laporan Anda ke administrator.</p>
                </div>
                <button type="button" onclick="closeLaporModal()"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('manager.laporan.store') }}" id="form-lapor">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider block">
                            Laporan
                        </label>
                        <textarea name="pertanyaan" rows="6" required
                                  placeholder="Tuliskan laporan Anda..."
                                  class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all resize-none"></textarea>
                        <p class="text-xs text-red-600 error-msg hidden" id="error-pertanyaan"></p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeLaporModal()"
                            class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-lapor"
                            class="px-5 py-2 bg-[#3B28CC] text-white text-sm font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openLaporModal() {
    const modal = document.getElementById('modal-lapor');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLaporModal() {
    document.getElementById('modal-lapor').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('form-lapor')?.addEventListener('submit', function() {
    const btn = document.getElementById('btn-submit-lapor');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Mengirim...';
});

document.addEventListener('DOMContentLoaded', () => {
    initRealTimeValidation('form-lapor');
});
</script>
@endsection
