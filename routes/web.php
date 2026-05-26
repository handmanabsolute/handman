<?php

use App\Http\Controllers\c_auth;
use App\Http\Controllers\c_kelolaAkun;
use App\Http\Controllers\c_departemen;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
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
    Route::get('/login/otp', [c_auth::class, 'showOtp'])->name('otp.view');
    Route::post('/login/otp', [c_auth::class, 'verifyOtp'])->name('otp.verify');
    Route::get('/login/otp/resend', [c_auth::class, 'resendOtp'])->name('otp.resend');

    Route::get('/forgot-password', [c_auth::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [c_auth::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [c_auth::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [c_auth::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [c_auth::class, 'logout'])->name('logout');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/kelola-akun', [c_kelolaAkun::class, 'index'])->name('kelola-akun.index');
        Route::get('/kelola-akun/create', [c_kelolaAkun::class, 'create'])->name('kelola-akun.create');
        Route::post('/kelola-akun', [c_kelolaAkun::class, 'store'])->name('kelola-akun.store');
        Route::get('/kelola-akun/{id}', [c_kelolaAkun::class, 'show'])->name('kelola-akun.show');
        Route::get('/kelola-akun/{id}/edit', [c_kelolaAkun::class, 'edit'])->name('kelola-akun.edit');
        Route::put('/kelola-akun/{id}', [c_kelolaAkun::class, 'update'])->name('kelola-akun.update');
        Route::delete('/kelola-akun/{id}', [c_kelolaAkun::class, 'destroy'])->name('kelola-akun.destroy');

        Route::get('/departemen/create', [c_departemen::class, 'create'])->name('departemen.create');
        Route::post('/departemen', [c_departemen::class, 'store'])->name('departemen.store');
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/dashboard', function () {
            return view('manager.dashboard');
        })->name('manager.dashboard');
    });

    Route::middleware('role:staff')->group(function () {
        Route::get('/staff/dashboard', function () {
            return view('staff.dashboard');
        })->name('staff.dashboard');
    });
});
