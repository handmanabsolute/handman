@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4">
    <div class="max-w-md w-full text-center space-y-6 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-50 text-red-600 mb-2">
            <i class="fa-solid fa-ban text-2xl"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-bold text-gray-900">Akun Anda Ditangguhkan</h1>
            <p class="text-sm text-gray-500 leading-relaxed">
                Maaf, akun Anda saat ini sedang dinonaktifkan atau dalam status skorsing oleh pihak manajemen. Anda tidak dapat mengakses sistem untuk sementara waktu.
            </p>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 text-left border border-gray-100 text-xs text-gray-600 space-y-1">
            <p class="font-medium text-gray-700">Langkah yang dapat dilakukan:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Hubungi divisi Admin atau HRD perusahaan Anda.</li>
                <li>Pastikan status kepegawaian Anda telah diperbarui.</li>
            </ul>
        </div>

        <div class="pt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center w-full px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Kembali ke Login
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
