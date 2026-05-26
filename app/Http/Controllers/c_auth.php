<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class c_auth extends Controller
{
    private function redirectByUserRole($user)
    {
        return match ($user->nama_role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'staff'   => redirect()->route('staff.dashboard'),
            default   => redirect('/dashboard'),
        };
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByUserRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_input'    => 'required|email',
            'password_input' => 'required|string',
        ], [
            'login_input.required'    => 'Email wajib diisi.',
            'login_input.email'       => 'Format email tidak valid.',
            'password_input.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $request->login_input)->first();

        if (!$user || !Hash::check($request->password_input, $user->password)) {
            throw ValidationException::withMessages([
                'login_input' => ['Email atau password yang Anda masukkan salah.'],
            ]);
        }

        if ($user->otp_code && $user->otp_expires_at && now()->lt($user->otp_expires_at)) {
            session([
                'auth_otp_user_id'  => $user->id,
                'auth_otp_remember' => $request->has('remember')
            ]);

            return redirect()->route('otp.view');
        }

        $otp = rand(100000, 999999);

        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        session([
            'auth_otp_user_id'  => $user->id,
            'auth_otp_remember' => $request->has('remember')
        ]);

        Mail::raw("Kode verifikasi OTP Anda adalah: $otp. Kode ini berlaku selama 5 menit.", function ($message) use ($user) {
            $message->to($user->email)->subject('Kode Verifikasi OTP Login');
        });

        return redirect()->route('otp.view');
    }

    public function showOtp()
    {
        if (Auth::check()) {
            return $this->redirectByUserRole(Auth::user());
        }

        if (!session()->has('auth_otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_input' => 'required|numeric',
        ], [
            'otp_input.required' => 'Kode OTP wajib diisi.',
            'otp_input.numeric'  => 'Kode OTP harus berupa angka.',
        ]);

        if (!session()->has('auth_otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('auth_otp_user_id'));

        if (!$user || !$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
            if ($user) {
                $user->update(['otp_code' => null, 'otp_expires_at' => null]);
            }
            session()->forget(['auth_otp_user_id', 'auth_otp_remember']);
            return redirect()->route('login')->withErrors(['login_input' => 'Sesi OTP telah habis, silakan login kembali.']);
        }

        if ((string)$request->otp_input !== (string)$user->otp_code) {
            throw ValidationException::withMessages([
                'otp_input' => ['Validasi OTP gagal.'],
            ]);
        }

        Auth::login($user, session('auth_otp_remember', false));

        $user->update(['otp_code' => null, 'otp_expires_at' => null]);

        session()->forget(['auth_otp_user_id', 'auth_otp_remember']);
        $request->session()->regenerate();

        return $this->redirectByUserRole($user);
    }

    public function resendOtp(Request $request)
    {
        if (!session()->has('auth_otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('auth_otp_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        $otp = rand(100000, 999999);

        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::raw("Kode verifikasi OTP baru Anda adalah: $otp. Kode ini berlaku selama 5 menit.", function ($message) use ($user) {
            $message->to($user->email)->subject('Kode Verifikasi OTP Baru');
        });

        return redirect()->route('otp.view')->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username_input' => 'required|email',
            'password_input' => 'required|string|min:8|confirmed',
        ], [
            'username_input.required' => 'Email wajib diisi.',
            'username_input.email'    => 'Format email tidak valid.',
            'password_input.required' => 'Password baru wajib diisi.',
            'password_input.min'      => 'Password minimal harus 8 karakter.',
            'password_input.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::where('email', $request->username_input)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username_input' => ['Akun dengan email tersebut tidak ditemukan.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password_input),
        ]);

        return redirect()->route('login')->with('status', 'Password berhasil diperbarui, silakan login kembali.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
