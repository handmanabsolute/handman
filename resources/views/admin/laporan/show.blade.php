@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="space-y-6 pb-10">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Laporan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap pengaduan dari pegawai beserta tanggapan admin.</p>
        </div>
        <div>
            <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
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
                <div class="flex items-center gap-3">
                    @if($laporan->user->foto_profil)
                        <img src="{{ asset('storage/' . $laporan->user->foto_profil) }}"
                             class="w-12 h-12 rounded-full object-cover border border-indigo-50 shrink-0">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($laporan->user->nama_lengkap) }}&background=3B28CC&color=fff&size=96"
                             class="w-12 h-12 rounded-full object-cover border border-indigo-50 shrink-0">
                    @endif
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-gray-900 truncate">{{ $laporan->user->nama_lengkap }}</h2>
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-md uppercase tracking-wider
                                {{ $laporan->user->nama_role === 'manager' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                {{ $laporan->user->nama_role }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $laporan->user->departemen->nama_departemen ?? '-' }} &bull; {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y, H:i') }}</p>
                    </div>
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

            
            <div class="space-y-4">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Pertanyaan</span>
                    </div>
                    <div class="text-sm text-gray-700 leading-relaxed bg-gray-50/60 p-5 rounded-2xl border border-gray-100 font-medium whitespace-pre-line">
                        {{ $laporan->isi }}
                    </div>
                </div>

                
                <div class="border-t border-gray-100 pt-6">
                    @if($laporan->tanggapan)
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Jawaban</span>
                            <div class="bg-indigo-50/30 border border-indigo-50/50 rounded-2xl p-5 space-y-3">
                                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $laporan->tanggapan }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-400 pt-2 border-t border-indigo-100/30">
                                    <span>Ditanggapi oleh <span class="font-semibold text-gray-600">{{ $laporan->responder->nama_lengkap ?? 'Admin' }}</span></span>
                                    <span>{{ \Carbon\Carbon::parse($laporan->responded_at)->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Kirim Respon Tanggapan</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Kirim tanggapan resmi untuk merespon pertanyaan pengaduan ini.</p>
                            </div>

                            <form method="POST" action="{{ route('admin.laporan.respon', $laporan->id) }}" id="form-respon">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-gray-600 uppercase tracking-wider block">
                                            Pesan Tanggapan <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="tanggapan" id="respon-tanggapan" rows="5" required
                                                  placeholder="Tuliskan respon tanggapan resmi Anda..."
                                                  class="w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] transition-all resize-none"></textarea>
                                        <p class="text-xs text-red-600 error-msg hidden" id="error-tanggapan"></p>
                                    </div>

                                    <div class="flex items-center justify-end gap-3 pt-2">
                                        <button type="submit" id="btn-submit-respon"
                                                class="px-5 py-2.5 bg-[#3B28CC] text-white text-sm font-semibold rounded-xl hover:bg-[#2c1fa3] transition-colors cursor-pointer flex items-center gap-2 shadow-xs">
                                            <i class="fa-solid fa-paper-plane text-xs"></i>
                                            Kirim Tanggapan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

<script>
document.getElementById('form-respon')?.addEventListener('submit', function() {
    const btn = document.getElementById('btn-submit-respon');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Mengirim...';
});

document.addEventListener('DOMContentLoaded', () => {
    initRealTimeValidation('form-respon');
});
</script>
@endsection
