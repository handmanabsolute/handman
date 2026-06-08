<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

test('forgot password page is accessible', function () {
    $response = $this->get('/forgot-password');
    $response->assertStatus(200);
});

test('submitting forgot password with invalid email returns validation error', function () {
    $response = $this->post('/forgot-password', [
        'username_input' => 'nonexistent@example.com',
    ]);
    $response->assertSessionHasErrors('username_input');
});

test('submitting forgot password with valid email sends email and stores token', function () {
    Mail::fake();

    $user = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Test User',
        'email'         => 'test@example.com',
        'password'      => Hash::make('oldpassword123'),
        'no_telp'       => '081234567891',
        'jenis_kelamin' => 'Laki-laki',
        'tanggal_lahir' => '1995-01-01',
        'nama_role'     => 'staff',
        'is_active'     => 1,
    ]);

    $response = $this->post('/forgot-password', [
        'username_input' => 'test@example.com',
    ]);

    // Assert redirected back with status
    $response->assertRedirect();
    $response->assertSessionHas('status');

    // Assert token stored in DB
    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => 'test@example.com',
    ]);

    // Assert mail sent
    Mail::assertSent(ResetPasswordMail::class, function ($mail) {
        return $mail->hasTo('test@example.com') && str_contains($mail->resetLink, '/reset-password/');
    });
});

test('reset password page is accessible with email query param', function () {
    $response = $this->get('/reset-password/sometoken?email=test@example.com');
    $response->assertStatus(200);
});

test('submitting reset password with valid token resets password', function () {
    $user = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Test User',
        'email'         => 'test@example.com',
        'password'      => Hash::make('oldpassword123'),
        'no_telp'       => '081234567891',
        'jenis_kelamin' => 'Laki-laki',
        'tanggal_lahir' => '1995-01-01',
        'nama_role'     => 'staff',
        'is_active'     => 1,
    ]);

    $token = Str::random(64);

    // Insert token record
    DB::table('password_reset_tokens')->insert([
        'email'      => 'test@example.com',
        'token'      => Hash::make($token),
        'created_at' => now(),
    ]);

    $response = $this->post('/reset-password', [
        'token'                       => $token,
        'username_input'              => 'test@example.com',
        'password_input'              => 'newpassword123',
        'password_input_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHas('status');

    // Assert password changed
    $user->refresh();
    $this->assertTrue(Hash::check('newpassword123', $user->password));

    // Assert token deleted
    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => 'test@example.com',
    ]);
});

test('submitting reset password with invalid token fails', function () {
    $user = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Test User',
        'email'         => 'test@example.com',
        'password'      => Hash::make('oldpassword123'),
        'no_telp'       => '081234567891',
        'jenis_kelamin' => 'Laki-laki',
        'tanggal_lahir' => '1995-01-01',
        'nama_role'     => 'staff',
        'is_active'     => 1,
    ]);

    DB::table('password_reset_tokens')->insert([
        'email'      => 'test@example.com',
        'token'      => Hash::make('correcttoken'),
        'created_at' => now(),
    ]);

    $response = $this->post('/reset-password', [
        'token'                       => 'wrongtoken',
        'username_input'              => 'test@example.com',
        'password_input'              => 'newpassword123',
        'password_input_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('password_input');
    $this->assertFalse(Hash::check('newpassword123', $user->fresh()->password));
});
