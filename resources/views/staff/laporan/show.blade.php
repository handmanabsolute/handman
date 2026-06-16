@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="space-y-6 pb-10">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Laporan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Informasi detail laporan masalah yang Anda ajukan.</p>
        </div>
        <div>
            <a href="{{ route('staff.laporan.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
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

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-2 bg-[#3B28CC]"></div>
        
        <div class="p-6 sm:p-8 space-y-6">
            
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 pb-5">
                <div class="min-w-0">
                    <p class="text-xs text-gray-400">Dikirim pada {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y, H:i') }}</p>
                </div>

                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg border
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
                </div>
            </div>

            
            <div class="space-y-6">
                
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Laporan</span>
                        @if(!$laporan->tanggapan)
                            <button type="button" onclick="openEditModal({{ json_encode($laporan) }})"
                                    class="text-[#3B28CC] hover:underline text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                                <i class="fa-solid fa-pen text-xs"></i>
                                Edit Laporan
                            </button>
                        @endif
                    </div>
                    <div class="text-sm text-gray-700 leading-relaxed bg-gray-50/60 p-5 rounded-2xl border border-gray-100 font-medium whitespace-pre-line">
                        {{ $laporan->isi }}
                    </div>
                </div>

                
                <div class="border-t border-gray-100 pt-6 space-y-2">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Jawaban</span>
                    @if($laporan->tanggapan)
                        <div class="bg-indigo-50/30 border border-indigo-50/50 rounded-2xl p-5 space-y-3">
                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $laporan->tanggapan }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-400 pt-2 border-t border-indigo-100/30">
                                <span>Ditanggapi oleh <span class="font-semibold text-gray-600">{{ $laporan->responder->nama_lengkap ?? 'Admin' }}</span></span>
                                <span>{{ \Carbon\Carbon::parse($laporan->responded_at)->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50/40 border border-gray-100 rounded-2xl p-5 text-center">
                            <p class="text-xs text-gray-400 italic">Belum ada jawaban dari Admin.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

<div id="modal-edit" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-xs" onclick="closeEditModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Edit Laporan</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Ubah laporan sebelum dijawab.</p>
                </div>
                <button type="button" onclick="closeEditModal()"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('laporan.update', $laporan->id) }}" id="form-edit">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider block">
                            Laporan
                        </label>
                        <textarea name="pertanyaan" id="edit-pertanyaan" rows="6" required
                                  placeholder="Tuliskan laporan Anda..."
                                  class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all resize-none"></textarea>
                        <p class="text-xs text-red-600 error-msg hidden" id="error-pertanyaan"></p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-edit"
                            class="px-5 py-2 bg-[#3B28CC] text-white text-sm font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-save text-xs"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(laporan) {
    document.getElementById('edit-pertanyaan').value = laporan.isi;
    
    const modal = document.getElementById('modal-edit');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('edit-pertanyaan').focus();
}

function closeEditModal() {
    document.getElementById('modal-edit').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('form-edit')?.addEventListener('submit', function() {
    const btn = document.getElementById('btn-submit-edit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Menyimpan...';
});

document.addEventListener('DOMContentLoaded', () => {
    initRealTimeValidation('form-edit');
});
</script>
@endsection
