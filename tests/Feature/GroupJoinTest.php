<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Models\Departemen;
use App\Models\GrupKerja;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

test('manager can add staff in division to a group work and then remove them', function () {
    // 1. Create departemen
    $departemen = Departemen::create([
        'nama_departemen' => 'Engineering',
        'deskripsi_departemen' => 'Engineering division',
    ]);

    // 2. Create manager
    $manager = User::create([
        'nama_lengkap'  => 'Manager Engineering',
        'email'         => 'manager@example.com',
        'password'      => Hash::make('Password123!'),
        'no_telp'       => '081234567890',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '1985-05-05',
        'nama_role'     => 'manager',
        'departemen_id' => $departemen->id,
        'is_active'     => 1,
    ]);

    // 3. Create staff in same division
    $staff = User::create([
        'nama_lengkap'  => 'Staff Engineering',
        'email'         => 'staff@example.com',
        'password'      => Hash::make('Password123!'),
        'no_telp'       => '081234567891',
        'jenis_kelamin' => 'P',
        'tanggal_lahir' => '1995-10-10',
        'nama_role'     => 'staff',
        'departemen_id' => $departemen->id,
        'is_active'     => 1,
    ]);

    // 4. Create group work
    $grup = GrupKerja::create([
        'nama_grup'     => 'Alpha Project',
        'deskripsi'     => 'Alpha project description',
        'departemen_id' => $departemen->id,
        'created_by'    => $manager->id,
    ]);

    // 5. Join Group
    $response = $this->actingAs($manager)
        ->post(route('staff-divisi.join-group', $staff->id), [
            'grup_kerja_id' => $grup->id,
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Staff berhasil dimasukkan ke dalam grup kerja.');

    // Assert pivot table detail_grups has the entry and a valid ULID id
    $this->assertDatabaseHas('detail_grups', [
        'grup_kerja_id' => $grup->id,
        'user_id'       => $staff->id,
    ]);

    $pivot = \DB::table('detail_grups')
        ->where('grup_kerja_id', $grup->id)
        ->where('user_id', $staff->id)
        ->first();
    
    $this->assertNotNull($pivot->id);
    $this->assertTrue(strlen($pivot->id) === 26); // ULID format length

    // 6. Leave Group
    $responseLeave = $this->actingAs($manager)
        ->post(route('staff-divisi.leave-group', $staff->id), [
            'grup_kerja_id' => $grup->id,
        ]);

    $responseLeave->assertRedirect();
    $responseLeave->assertSessionHas('success', 'Staff berhasil dikeluarkan dari grup kerja.');

    $this->assertDatabaseMissing('detail_grups', [
        'grup_kerja_id' => $grup->id,
        'user_id'       => $staff->id,
    ]);
});
