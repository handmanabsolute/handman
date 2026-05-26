@extends('layouts.app')

@section('content')
<div class="w-full h-screen flex flex-col md:flex-row overflow-hidden bg-white">

    <div class="w-full md:w-[50%] h-full p-8 md:p-16 flex flex-col justify-between items-stretch overflow-hidden">
        <div class="flex items-center gap-2 shrink-0">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo HandMan" class="w-8 h-8 object-contain">
            <span class="text-xl font-bold text-[#3B28CC]">HandMan</span>
        </div>

        <div class="max-w-100 w-full mx-auto flex flex-col justify-center grow py-4">
            <div class="text-center mb-6 shrink-0">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Reset Password</h1>
                <p class="text-sm text-gray-500">Silakan masukkan email Anda dan tentukan password baru</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-500 p-3 rounded-xl text-xs mb-4 text-center shrink-0">
                    <ul class="list-none">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" novalidate class="space-y-4 grow flex flex-col justify-center">
                @csrf

                <div>
                    <label for="username_input" class="block text-xs font-medium text-gray-500 mb-2 pl-1">Alamat Email</label>
                    <input type="email" id="username_input" name="username_input" value="{{ old('username_input') }}" placeholder="nama@email.com" class="w-full px-4 py-3 rounded-xl border border-transparent bg-[#F3F4F6] focus:border-[#3B28CC] focus:bg-white outline-none transition text-sm font-medium text-gray-800">
                </div>

                <div>
                    <label for="password_input" class="block text-xs font-medium text-gray-500 mb-2 pl-1">Password Baru</label>
                    <input type="password" id="password_input" name="password_input" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-transparent bg-[#F3F4F6] focus:border-[#3B28CC] focus:bg-white outline-none transition text-sm font-medium text-gray-800">
                </div>

                <div>
                    <label for="password_input_confirmation" class="block text-xs font-medium text-gray-500 mb-2 pl-1">Konfirmasi Password Baru</label>
                    <input type="password" id="password_input_confirmation" name="password_input_confirmation" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-transparent bg-[#F3F4F6] focus:border-[#3B28CC] focus:bg-white outline-none transition text-sm font-medium text-gray-800">
                </div>

                <button type="submit" class="w-full bg-[#3B28CC] hover:bg-[#2A1BA3] text-white font-medium py-3 rounded-full transition shadow-lg shadow-indigo-100 mt-2 cursor-pointer">
                    Perbarui Password
                </button>
            </form>

            <div class="text-center mt-6 text-sm text-gray-500 shrink-0">
                <a href="{{ route('login') }}" class="font-semibold text-[#3B28CC] hover:underline">&larr; Kembali ke Halaman Login</a>
            </div>
        </div>
    </div>

    <div class="hidden md:flex w-[50%] h-full bg-[#3B28CC] flex-col items-center justify-center text-white text-center p-16 overflow-hidden">
        <div class="w-72 h-72 lg:w-60 lg:h-60 bg-white rounded-full flex items-center justify-center p-6 shadow-inner mb-8 shrink-0">
            <img src="{{ asset('assets/logo.png') }}" alt="Ilustrasi" class="w-full h-full object-contain">
        </div>
        <h2 class="text-3xl lg:text-4xl font-bold mb-4 tracking-wide leading-tight shrink-0">Sistem Management<br>Tugas Kantor</h2>
        <p class="text-sm lg:text-base opacity-80 font-light mt-2 shrink-0">Nama Perusahaan</p>
    </div>

</div>
@endsection
