<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

test('login requires credentials and redirects to otp page', function () {
    Mail::fake();

    // Create user
    $user = User::create([
        'id' => (string) Str::ulid(),
        'nama_lengkap' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
        'no_telp' => '081234567891',
        'jenis_kelamin' => 'Laki-laki',
        'tanggal_lahir' => '1995-01-01',
        'nama_role' => 'staff',
        'is_active' => 1,
    ]);

    // Attempt login
    $response = $this->post('/login', [
        'login_input' => 'test@example.com',
        'password_input' => 'password123',
    ]);

    // Assert redirect to OTP page
    $response->assertRedirect('/login/otp');

    // Assert OTP session data set
    $this->assertEquals($user->id, session('otp_user_id'));

    // Assert user has otp code in database
    $user->refresh();
    $this->assertNotNull($user->otp_code);
    $this->assertNotNull($user->otp_expires_at);

    // Assert mail was sent with correct OTP
    Mail::assertSent(SendOtpMail::class, function ($mail) use ($user) {
        return $mail->otpCode === $user->otp_code && $mail->hasTo($user->email);
    });
});

test('submitting correct OTP authenticates user and redirects', function () {
    Mail::fake();

    $user = User::create([
        'id' => (string) Str::ulid(),
        'nama_lengkap' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
        'no_telp' => '081234567891',
        'jenis_kelamin' => 'Laki-laki',
        'tanggal_lahir' => '1995-01-01',
        'nama_role' => 'staff',
        'is_active' => 1,
    ]);

    // Attempt login to set session
    $this->post('/login', [
        'login_input' => 'test@example.com',
        'password_input' => 'password123',
    ]);

    $user->refresh();
    $otp = $user->otp_code;

    // Verify OTP
    $response = $this->post('/login/otp', [
        'otp_input' => $otp,
    ]);

    // Assert redirected to dashboard based on role
    $response->assertRedirect('/staff/dashboard');

    // Assert authenticated
    $this->assertAuthenticatedAs($user);

    // Assert OTP cleared in database
    $user->refresh();
    $this->assertNull($user->otp_code);
    $this->assertNull($user->otp_expires_at);
});

test('submitting incorrect OTP returns error', function () {
    Mail::fake();

    $user = User::create([
        'id' => (string) Str::ulid(),
        'nama_lengkap' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
        'no_telp' => '081234567891',
        'jenis_kelamin' => 'Laki-laki',
        'tanggal_lahir' => '1995-01-01',
        'nama_role' => 'staff',
        'is_active' => 1,
    ]);

    // Attempt login to set session
    $this->post('/login', [
        'login_input' => 'test@example.com',
        'password_input' => 'password123',
    ]);

    // Verify incorrect OTP
    $response = $this->post('/login/otp', [
        'otp_input' => '000000', // incorrect code
    ]);

    $response->assertSessionHasErrors('otp_input');
    $this->assertGuest();
});
