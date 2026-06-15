<?php

use App\Http\Controllers\c_auth;
use App\Http\Controllers\c_kelolaAkun;
use App\Http\Controllers\c_departemen;
use App\Http\Controllers\c_kelolaTugas;
use App\Http\Controllers\c_adminTugas;
use App\Http\Controllers\c_adminDashboard;
use App\Http\Controllers\c_staffDivisi;
use App\Http\Controllers\c_grupKerja;
use App\Http\Controllers\c_profil;
use App\Http\Controllers\c_laporan;
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

    Route::get('/notifications/{id}/read', [\App\Http\Controllers\c_notification::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\c_notification::class, 'readAll'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\c_notification::class, 'destroy'])->name('notifications.destroy');

    Route::get('/laporan/{id}', [c_laporan::class, 'show'])->name('laporan.show');
    Route::put('/laporan/{id}', [c_laporan::class, 'update'])->name('laporan.update');

    Route::post('/jadwal/notes', [\App\Http\Controllers\c_kelolaJadwal::class, 'storeNote'])->name('jadwal.notes.store');
    Route::put('/jadwal/notes/{id}', [\App\Http\Controllers\c_kelolaJadwal::class, 'updateNote'])->name('jadwal.notes.update');
    Route::delete('/jadwal/notes/{id}', [\App\Http\Controllers\c_kelolaJadwal::class, 'destroyNote'])->name('jadwal.notes.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [c_adminDashboard::class, 'index'])->name('admin.dashboard');
        Route::resource('kelola-akun', c_kelolaAkun::class);
        Route::resource('departemen', c_departemen::class);
        Route::get('/admin/tugas/export-pdf', [c_adminTugas::class, 'exportPdf'])->name('admin.tugas.exportPdf');
        Route::get('/admin/tugas', [c_adminTugas::class, 'index'])->name('admin.tugas.index');

        Route::get('/admin/laporan', [c_laporan::class, 'index'])->name('admin.laporan.index');
        Route::put('/admin/laporan/{id}/respon', [c_laporan::class, 'respond'])->name('admin.laporan.respon');
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/dashboard', function () {
            $departemenId = auth()->user()->departemen_id;

            $totalTugas = \App\Models\Tugas::where('departemen_id', $departemenId)->count();
            $tugasSelesai = \App\Models\Tugas::where('departemen_id', $departemenId)->where('status_tugas', 'Selesai')->count();
            $tugasPending = \App\Models\Tugas::where('departemen_id', $departemenId)->where('status_tugas', 'Menunggu Persetujuan')->count();
            $tugasRevisi = \App\Models\Tugas::where('departemen_id', $departemenId)->where('status_tugas', 'Revisi')->count();
            $tugasBerjalan = \App\Models\Tugas::where('departemen_id', $departemenId)->whereNotIn('status_tugas', ['Selesai', 'Menunggu Persetujuan'])->count();
            $efisiensi = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100) : 0;

            $staffCount = \App\Models\User::where('departemen_id', $departemenId)->where('nama_role', 'staff')->count();
            $totalGrup = \App\Models\GrupKerja::where('departemen_id', $departemenId)->count();
            $totalLaporan = \App\Models\Laporan::whereHas('user', function($q) use ($departemenId) { $q->where('departemen_id', $departemenId); })->count();
            $tugasKelompok = \App\Models\Tugas::where('departemen_id', $departemenId)->where('kategoritugas', 'Kelompok')->count();

            $tugas = \App\Models\Tugas::where('departemen_id', $departemenId) ->latest() ->take(5) ->get();

            $laporans = \App\Models\Laporan::whereHas('user', function($q) use ($departemenId) {
                    $q->where('departemen_id', $departemenId);
                })
                ->latest() ->take(5) ->get();

            return view('manager.dashboard', compact(
                'tugas', 'totalTugas', 'tugasSelesai', 'tugasPending', 'tugasRevisi',
                'tugasBerjalan', 'efisiensi', 'staffCount', 'totalGrup', 'totalLaporan',
                'tugasKelompok', 'laporans'
            ));
        })->name('manager.dashboard');
        Route::get('/tugas/export-pdf', [c_kelolaTugas::class, 'exportPdf'])->name('tugas.exportPdf');
        Route::resource('tugas', c_kelolaTugas::class);
        Route::put('/tugas/{id}/review', [c_kelolaTugas::class, 'reviewTugas'])->name('tugas.review');

        Route::get('/jadwal', [\App\Http\Controllers\c_kelolaJadwal::class, 'index'])->name('jadwal.index');

        Route::get('/staff-divisi', [c_staffDivisi::class, 'index'])->name('staff-divisi.index');
        Route::get('/staff-divisi/{id}', [c_staffDivisi::class, 'show'])->name('staff-divisi.show');
        Route::post('/staff-divisi/{id}/join-group', [c_staffDivisi::class, 'joinGroup'])->name('staff-divisi.join-group');
        Route::post('/staff-divisi/{id}/leave-group', [c_staffDivisi::class, 'leaveGroup'])->name('staff-divisi.leave-group');

        Route::resource('grup-kerja', c_grupKerja::class);

        Route::get('/laporan', [c_laporan::class, 'index'])->name('manager.laporan.index');
        Route::post('/laporan', [c_laporan::class, 'store'])->name('manager.laporan.store');
    });

    Route::middleware('role:staff')->group(function () {
        Route::get('/staff/dashboard', function () {
            $departemenId = auth()->user()->departemen_id;
            $userId = auth()->id();

            $myGrupIds = \App\Models\GrupKerja::whereHas('anggota', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            })->pluck('id');

            $tugasQuery = \App\Models\Tugas::where('departemen_id', $departemenId)
                ->where(function ($query) use ($userId, $myGrupIds) {
                    $query->whereHas('detailTugas', function ($q) use ($userId, $myGrupIds) {
                        $q->where('user_id', $userId) ->orWhereIn('grup_kerja_id', $myGrupIds);
                    })
                    ->orWhereDoesntHave('detailTugas');
                });

            $totalTugas = (clone $tugasQuery)->count();
            $tugasSelesai = (clone $tugasQuery)->where('status_tugas', 'Selesai')->count();
            $tugasPending = (clone $tugasQuery)->where('status_tugas', 'Menunggu Persetujuan')->count();
            $tugasRevisi = (clone $tugasQuery)->where('status_tugas', 'Revisi')->count();
            $tugasBerjalan = (clone $tugasQuery)->whereNotIn('status_tugas', ['Selesai', 'Menunggu Persetujuan'])->count();
            $efisiensi = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100) : 0;

            $totalGrupSaya = \App\Models\GrupKerja::whereHas('anggota', function($q) use ($userId) {
                $q->where('users.id', $userId);
            })->count();
            $totalLaporanSaya = \App\Models\Laporan::where('user_id', $userId)->count();
            $tugasKelompokSaya = (clone $tugasQuery)->where('kategoritugas', 'Kelompok')->count();
            $tugasIndividuSaya = (clone $tugasQuery)->where('kategoritugas', 'Individu')->count();

            $tugas = (clone $tugasQuery) ->latest() ->take(5) ->get();
            $laporans = \App\Models\Laporan::where('user_id', $userId) ->latest() ->take(5) ->get();

            return view('staff.dashboard', compact(
                'tugas', 'totalTugas', 'tugasSelesai', 'tugasPending', 'tugasRevisi',
                'tugasBerjalan', 'efisiensi', 'totalGrupSaya', 'totalLaporanSaya',
                'tugasKelompokSaya', 'tugasIndividuSaya', 'laporans'
            ));
        })->name('staff.dashboard');

        Route::get('/staff/tugas', [c_kelolaTugas::class, 'index'])->name('staff.tugas.index');
        Route::get('/staff/tugas/{id}', [c_kelolaTugas::class, 'show'])->name('staff.tugas.show');
        Route::post('/tugas/{id}/submit', [c_kelolaTugas::class, 'submitTugas'])->name('tugas.submit');
        Route::put('/lampiran/{id}/update', [c_kelolaTugas::class, 'updateLampiran'])->name('lampiran.update');
        Route::delete('/lampiran/{id}/hapus', [c_kelolaTugas::class, 'hapusSubmit'])->name('lampiran.destroy');

        Route::get('/staff/jadwal', [\App\Http\Controllers\c_kelolaJadwal::class, 'index'])->name('staff.jadwal.index');

        Route::get('/staff/laporan', [c_laporan::class, 'index'])->name('staff.laporan.index');
        Route::post('/staff/laporan', [c_laporan::class, 'store'])->name('staff.laporan.store');
    });
});
