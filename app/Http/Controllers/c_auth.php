<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class c_auth extends Controller
{
    private function redirectByUserRole($user)
    {
        if ($user->is_active == 0) {
            return redirect()->route('blocked.page');
        }

        return match ($user->nama_role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect('/dashboard'),
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
            'login_input' => 'required|email',
            'password_input' => 'required|string',
        ], [
            'login_input.required' => 'Email wajib diisi.',
            'login_input.email' => 'Format email tidak valid.',
            'password_input.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $request->login_input)->first();

        if (! $user || ! Hash::check($request->password_input, $user->password)) {
            throw ValidationException::withMessages([
                'login_input' => ['Email atau password yang Anda masukkan salah.'],
            ]);
        }

        $otp = sprintf('%06d', mt_rand(100000, 999999));
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        try {
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            Log::error('Error sending OTP mail: '.$e->getMessage());
            throw ValidationException::withMessages([
                'login_input' => ['Gagal mengirim kode OTP ke email Anda. Silakan coba beberapa saat lagi.'],
            ]);
        }

        session([
            'otp_user_id' => $user->id,
            'otp_remember' => $request->has('remember'),
        ]);

        return redirect()->route('otp');
    }

    public function showOtp()
    {
        if (! session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_input' => 'required|string|size:6',
        ], [
            'otp_input.required' => 'Kode OTP wajib diisi.',
            'otp_input.size' => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        if (! session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('otp_user_id'));

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->otp_code !== $request->otp_input || now()->greaterThan($user->otp_expires_at)) {
            throw ValidationException::withMessages([
                'otp_input' => ['Kode OTP salah atau telah kadaluarsa.'],
            ]);
        }

        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        Auth::login($user, session('otp_remember', false));
        $request->session()->regenerate();

        session()->forget(['otp_user_id', 'otp_remember']);

        return $this->redirectByUserRole($user);
    }

    public function resendOtp()
    {
        if (! session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('otp_user_id'));

        if (! $user) {
            return redirect()->route('login');
        }

        $otp = sprintf('%06d', mt_rand(100000, 999999));
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        try {
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            Log::error('Error resending OTP mail: '.$e->getMessage());

            return redirect()->back()->withErrors([
                'otp_input' => 'Gagal mengirim ulang kode OTP. Silakan coba beberapa saat lagi.',
            ]);
        }

        return redirect()->back()->with('status', 'Kode OTP baru telah dikirimkan ke email Anda.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'username_input' => 'required|email|exists:users,email',
        ], [
            'username_input.required' => 'Email wajib diisi.',
            'username_input.email' => 'Format email tidak valid.',
            'username_input.exists' => 'Email tidak terdaftar.',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->username_input],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetLink = route('password.reset', ['token' => $token, 'email' => $request->username_input]);

        try {
            Mail::to($request->username_input)->send(new ResetPasswordMail($resetLink));
        } catch (\Exception $e) {
            Log::error('Error sending reset password mail: '.$e->getMessage());

            return redirect()->back()->withErrors([
                'username_input' => 'Gagal mengirim email reset password. Silakan coba beberapa saat lagi.',
            ]);
        }

        return redirect()->back()->with('status', 'Link reset password telah dikirimkan ke email Anda.');
    }

    public function showResetPassword(Request $request, $token)
    {
        $email = $request->query('email');
        if (! $email) {
            abort(404);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'username_input' => 'required|email|exists:users,email',
            'password_input' => 'required|string|min:6',
            'password_input_confirmation' => 'required|same:password_input',
        ], [
            'token.required' => 'Token tidak valid.',
            'username_input.required' => 'Email wajib diisi.',
            'username_input.email' => 'Format email tidak valid.',
            'username_input.exists' => 'Email tidak terdaftar.',
            'password_input.required' => 'Password baru wajib diisi.',
            'password_input.min' => 'Password baru minimal harus 6 karakter.',
            'password_input_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
            'password_input_confirmation.same' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->username_input)
            ->first();

        if (! $resetRecord || ! Hash::check($request->token, $resetRecord->token)) {
            throw ValidationException::withMessages([
                'password_input' => ['Link reset password tidak valid atau telah kadaluarsa.'],
            ]);
        }

        if (now()->subMinutes(60)->greaterThan($resetRecord->created_at)) {
            DB::table('password_reset_tokens')->where('email', $request->username_input)->delete();
            throw ValidationException::withMessages([
                'password_input' => ['Link reset password telah kadaluarsa.'],
            ]);
        }

        $user = User::where('email', $request->username_input)->first();
        if ($user) {
            $user->password = Hash::make($request->password_input);
            $user->save();
        }

        DB::table('password_reset_tokens')->where('email', $request->username_input)->delete();

        return redirect()->route('login')->with('status', 'Password Anda berhasil diperbarui.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
