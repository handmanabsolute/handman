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
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Selamat Datang</h1>
                <p class="text-sm text-gray-500">Silahkan Login untuk Melanjutkan</p>
            </div>

            @if (session('status'))
                <div class="bg-green-50 text-green-600 p-3 rounded-xl text-sm mb-4 shrink-0">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('login_input') && !$errors->has('password_input'))
                <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-4 shrink-0">
                    {{ $errors->first('login_input') }}
                </div>
            @endif

            <form id="login_form" action="{{ route('login.submit') }}" method="POST" novalidate class="space-y-4 grow flex flex-col justify-center">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1.5">Email</label>
                    <input type="email" name="login_input" value="{{ old('login_input') }}" placeholder="Masukkan Email" class="w-full px-4 py-3 rounded-xl border border-transparent bg-[#F3F4F6] focus:border-[#3B28CC] focus:bg-white outline-none transition text-sm text-gray-800">
                    @if ($errors->has('login_input') && $errors->has('password_input'))
                        <p class="text-xs text-red-500 mt-1 pl-1">{{ $errors->first('login_input') }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="password_input" name="password_input" placeholder="Masukkan Password" class="w-full px-4 py-3 rounded-xl border border-transparent bg-[#F3F4F6] focus:border-[#3B28CC] focus:bg-white outline-none transition text-sm text-gray-800 pr-10">
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i id="password_icon" class="fa-regular fa-eye-slash"></i>
                        </button>
                    </div>
                    @error('password_input')
                        <p class="text-xs text-red-500 mt-1 pl-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-gray-500 select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#3B28CC] focus:ring-[#3B28CC]">
                        Ingat Saya
                    </label>
                    <a href="{{ route('password.request') }}" class="font-semibold text-[#3B28CC] hover:underline">Lupa Password</a>
                </div>

                <button type="submit" id="submit_button" class="w-full bg-[#3B28CC] hover:bg-[#2A1BA3] disabled:bg-gray-400 text-white font-medium py-3 rounded-full transition shadow-lg shadow-indigo-100 mt-2 cursor-pointer disabled:cursor-not-allowed">
                    Login
                </button>
            </form>
        </div>
    </div>

    <div class="hidden md:flex w-[50%] h-full bg-[#3B28CC] flex-col items-center justify-center text-white text-center p-16 overflow-hidden">
        <div class="w-72 h-72 lg:w-60 lg:h-60 bg-white rounded-full flex items-center justify-center p-6 shadow-inner mb-8 shrink-0">
            <img src="{{ asset('assets/logo.png') }}" alt="Ilustrasi" class="w-full h-full object-contain">
        </div>
        <h2 class="text-3xl lg:text-4xl font-bold mb-4 tracking-wide leading-tight shrink-0">Sistem Management<br>Tugas Kantor</h2>
    </div>

</div>
@endsection
