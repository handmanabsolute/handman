@extends('layouts.app')

@section('content')
<div class="w-full h-screen flex flex-col md:flex-row overflow-hidden bg-white">
    <div class="w-full md:w-[50%] h-full p-8 md:p-16 flex flex-col justify-between items-stretch overflow-hidden">
        <div class="flex items-center gap-2 shrink-0">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
            <span class="text-xl font-bold text-[#3B28CC]">HandMan</span>
        </div>

        <div class="max-w-100 w-full mx-auto flex flex-col justify-center grow py-4">
            <div class="text-center mb-6 shrink-0">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Verifikasi Keamanan</h1>
                <p class="text-sm text-gray-500">Kode OTP telah dikirimkan ke email terdaftar Anda</p>
            </div>

            @if (session('status'))
                <div class="bg-green-50 text-green-600 p-3 rounded-xl text-sm mb-4 text-center shrink-0">
                    {{ session('status') }}
                </div>
            @endif

            <form id="otp_form" action="{{ route('otp.verify') }}" method="POST" novalidate class="space-y-5 grow flex flex-col justify-center">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-500 text-center mb-3">Masukkan 6 Digit Kode OTP</label>
                    <input type="text" id="otp_input" name="otp_input" maxlength="6" inputmode="numeric" pattern="[0-9]*" placeholder="000000" autocomplete="one-time-code" class="w-full px-4 py-3 rounded-xl border border-transparent bg-[#F3F4F6] focus:border-[#3B28CC] focus:bg-white outline-none transition text-lg tracking-[0.75em] text-center font-bold text-gray-800">
                    @error('otp_input')
                        <p class="text-xs text-red-500 mt-2 pl-1 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" id="verify_button" class="w-full bg-[#3B28CC] hover:bg-[#2A1BA3] disabled:bg-gray-400 text-white font-medium py-3 rounded-full transition shadow-lg shadow-indigo-100 mt-2 cursor-pointer disabled:cursor-not-allowed">
                    Verifikasi OTP
                </button>
            </form>

            <div class="text-center mt-6 text-sm text-gray-500 shrink-0">
                Tidak menerima kode?
                <a href="{{ route('otp.resend') }}" class="font-semibold text-[#3B28CC] hover:underline ml-1">Kirim Ulang OTP</a>
            </div>
        </div>
    </div>

    <div class="hidden md:flex w-[50%] h-full bg-[#3B28CC] flex-col items-center justify-center text-white text-center p-16 overflow-hidden">
        <div class="w-72 h-72 lg:w-60 lg:h-60 bg-white rounded-full flex items-center justify-center p-6 shadow-inner mb-8 shrink-0">
            <img src="{{ asset('assets/logo.png') }}" alt="Ilustrasi" class="w-full h-full object-contain">
        </div>
        <h2 class="text-3xl lg:text-4xl font-bold mb-4 tracking-wide leading-tight shrink-0">Sistem Management<br>Tugas Kantor</h2>
    </div>
</div>

<script>
    const otpInput = document.getElementById('otp_input');
    otpInput.addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection
