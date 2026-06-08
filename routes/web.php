<?php

use App\Http\Controllers\c_auth;
use App\Http\Controllers\c_kelolaAkun;
use App\Http\Controllers\c_departemen;
use App\Http\Controllers\c_kelolaTugas;
use App\Http\Controllers\c_profil;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->is_active == 0) {
            return redirect()->route('blocked.page');
        }

        return match (Auth::user()->nama_role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect('/login'),
        };
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [c_auth::class, 'showLogin'])->name('login');
    Route::post('/login', [c_auth::class, 'login'])->name('login.submit');
    Route::get('/login/otp', [c_auth::class, 'showOtp'])->name('otp');
    Route::post('/login/otp', [c_auth::class, 'verifyOtp'])->name('otp.verify');
    Route::get('/login/otp/resend', [c_auth::class, 'resendOtp'])->name('otp.resend');
    Route::get('/forgot-password', [c_auth::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [c_auth::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [c_auth::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [c_auth::class, 'resetPassword'])->name('password.update');
});

Route::get('/blocked', function () {
    if (Auth::check() && Auth::user()->is_active != 0) {
        return match (Auth::user()->nama_role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect('/'),
        };
    }
    return view('auth.blocked');
})->name('blocked.page');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [c_auth::class, 'logout'])->name('logout');
    Route::get('/profil', [c_profil::class, 'show'])->name('profil.show');
    Route::get('/profil/edit', [c_profil::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [c_profil::class, 'update'])->name('profil.update');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
        Route::resource('kelola-akun', c_kelolaAkun::class);
        Route::resource('departemen', c_departemen::class);
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/dashboard', function () { return view('manager.dashboard'); })->name('manager.dashboard');
        Route::resource('tugas', c_kelolaTugas::class);
        Route::put('/tugas/{id}/review', [c_kelolaTugas::class, 'reviewTugas'])->name('tugas.review');
    });

    Route::middleware('role:staff')->group(function () {
        Route::get('/staff/dashboard', function () { return view('staff.dashboard'); })->name('staff.dashboard');
        Route::get('/staff/tugas', [c_kelolaTugas::class, 'index'])->name('staff.tugas.index');
        Route::get('/staff/tugas/{id}', [c_kelolaTugas::class, 'show'])->name('staff.tugas.show');
        Route::get('/staff/tugas/{id}/submit', [c_kelolaTugas::class, 'submitTugasForm'])->name('staff.tugas.submit.form');
        Route::post('/tugas/{id}/submit', [c_kelolaTugas::class, 'submitTugas'])->name('tugas.submit');
        Route::put('/lampiran/{id}/update', [c_kelolaTugas::class, 'updateLampiran'])->name('lampiran.update');
        Route::delete('/lampiran/{id}/hapus', [c_kelolaTugas::class, 'hapusSubmit'])->name('lampiran.destroy');
    });
});
