<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Models\Departemen;
use App\Mail\RandomPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

test('admin can create new user without password input and random password is sent via email', function () {
    Mail::fake();

    // 1. Create admin user
    $admin = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Admin User',
        'email'         => 'admin@example.com',
        'password'      => Hash::make('AdminPassword123!'),
        'no_telp'       => '081111111111',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '1990-01-01',
        'nama_role'     => 'admin',
        'is_active'     => 1,
    ]);

    // 2. Create departemen
    $departemen = Departemen::create([
        'nama_departemen' => 'IT Support',
        'deskripsi_departemen' => 'Departemen IT Support',
    ]);

    // 3. Act as admin and post data to store route without password
    $response = $this->actingAs($admin)
        ->post(route('kelola-akun.store'), [
            'nama_lengkap'   => 'Staff Baru',
            'email'          => 'staffbaru@example.com',
            'no_telp'        => '082222222222',
            'jenis_kelamin'  => 'P',
            'tanggal_lahir'  => '1998-05-15',
            'status_pegawai' => 'tetap',
            'nama_role'      => 'staff',
            'departemen_id'  => $departemen->id,
            'alamat'         => 'Jl. Baru No. 123',
            'deskripsi_user' => 'Deskripsi staff baru',
        ]);

    // 4. Assert response is redirect/JSON success
    $response->assertStatus(200);
    $response->assertJson([
        'redirect' => route('kelola-akun.index')
    ]);

    // 5. Assert user exists in database
    $this->assertDatabaseHas('users', [
        'email'        => 'staffbaru@example.com',
        'nama_lengkap' => 'Staff Baru',
        'nama_role'    => 'staff',
    ]);

    // Retrieve the created user and assert password is encrypted and valid
    $createdUser = User::where('email', 'staffbaru@example.com')->first();
    $this->assertNotNull($createdUser->password);

    // 6. Assert RandomPassword mail was sent with the correct password
    Mail::assertSent(RandomPassword::class, function ($mail) use ($createdUser) {
        // Assert recipient is correct
        $hasCorrectRecipient = $mail->hasTo($createdUser->email);
        
        // Assert password passed to email is the one hashed in db
        $passwordMatchesHash = Hash::check($mail->password, $createdUser->password);
        
        return $hasCorrectRecipient && $passwordMatchesHash;
    });
});
