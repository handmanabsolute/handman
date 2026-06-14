<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Models\Departemen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

test('authenticated user can access profile show and edit pages', function () {
    $user = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Test User',
        'email'         => 'test@example.com',
        'password'      => Hash::make('password123'),
        'no_telp'       => '081234567890',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '1995-01-01',
        'nama_role'     => 'staff',
        'is_active'     => 1,
    ]);

    $response = $this->actingAs($user)->get(route('profil.show'));
    $response->assertStatus(200);

    $response = $this->actingAs($user)->get(route('profil.edit'));
    $response->assertStatus(200);
});

test('admin can update password and upload photo but email remains unchanged', function () {
    Storage::fake('public');
    $admin = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Admin User',
        'email'         => 'admin@example.com',
        'password'      => Hash::make('OldPassword123!'),
        'no_telp'       => '081111111111',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '1990-01-01',
        'nama_role'     => 'admin',
        'is_active'     => 1,
    ]);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->actingAs($admin)->put(route('profil.update'), [
        'email'       => 'newadmin@example.com', // attempting to change email
        'password'    => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
        'foto_profil' => $file,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'redirect' => route('profil.show')
    ]);

    $admin->refresh();
    
    // Assert email remains admin@example.com
    $this->assertEquals('admin@example.com', $admin->email);
    
    // Assert password changed
    $this->assertTrue(Hash::check('NewPassword123!', $admin->password));

    // Assert photo uploaded and saved
    $this->assertNotNull($admin->foto_profil);
    Storage::disk('public')->assertExists($admin->foto_profil);
});

test('manager/staff can update allowed fields and photo but restricted fields remain unchanged', function () {
    Storage::fake('public');
    
    $departemen = Departemen::create([
        'nama_departemen' => 'IT',
    ]);
    
    $otherDepartemen = Departemen::create([
        'nama_departemen' => 'HRD',
    ]);

    $staff = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Original Name',
        'email'         => 'original@example.com',
        'password'      => Hash::make('password123'),
        'no_telp'       => '081234567890',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '1995-01-01',
        'nama_role'     => 'staff',
        'is_active'     => 1,
        'departemen_id' => $departemen->id,
        'status_pegawai'=> 'tetap',
        'alamat'        => 'Original Address',
        'deskripsi_user'=> 'Original Description',
    ]);

    $file = UploadedFile::fake()->image('profile_pic.png');

    $response = $this->actingAs($staff)->put(route('profil.update'), [
        // Attempting to change restricted fields
        'nama_lengkap'   => 'Hack Name',
        'email'          => 'hackemail@example.com',
        'nama_role'      => 'manager',
        'departemen_id'  => $otherDepartemen->id,
        'status_pegawai' => 'magang',
        // Legitimate fields
        'no_telp'        => '089999999999',
        'jenis_kelamin'  => 'P',
        'tanggal_lahir'  => '2000-02-02',
        'alamat'         => 'New Address',
        'deskripsi_user' => 'New Description',
        'password'       => 'NewPass123!',
        'password_confirmation' => 'NewPass123!',
        'foto_profil'    => $file,
    ]);

    $response->assertStatus(200);

    $staff->refresh();

    // Assert restricted fields remain unchanged
    $this->assertEquals('Original Name', $staff->nama_lengkap);
    $this->assertEquals('original@example.com', $staff->email);
    $this->assertEquals('staff', $staff->nama_role);
    $this->assertEquals($departemen->id, $staff->departemen_id);
    $this->assertEquals('tetap', $staff->status_pegawai);

    // Assert allowed fields are updated
    $this->assertEquals('089999999999', $staff->no_telp);
    $this->assertEquals('P', $staff->jenis_kelamin);
    $this->assertEquals('2000-02-02', $staff->tanggal_lahir->format('Y-m-d'));
    $this->assertEquals('New Address', $staff->alamat);
    $this->assertEquals('New Description', $staff->deskripsi_user);
    $this->assertTrue(Hash::check('NewPass123!', $staff->password));

    // Assert photo uploaded and saved
    $this->assertNotNull($staff->foto_profil);
    Storage::disk('public')->assertExists($staff->foto_profil);
});
