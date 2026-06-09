<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Models\Departemen;
use App\Models\Tugas;
use App\Models\CatatanJadwal;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

function createManager() {
    $departemen = Departemen::create([
        'nama_departemen' => 'Finance',
        'deskripsi_departemen' => 'Finance Department',
    ]);

    return User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Manager Keuangan',
        'email'         => 'manager@example.com',
        'password'      => Hash::make('Password123!'),
        'no_telp'       => '081222222222',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '1990-01-01',
        'nama_role'     => 'manager',
        'departemen_id'  => $departemen->id,
        'is_active'     => 1,
    ]);
}

test('manager can access index jadwal page', function () {
    $manager = createManager();

    $response = $this->actingAs($manager)
        ->get(route('jadwal.index'));

    $response->assertStatus(200);
    $response->assertViewIs('manager.jadwal.index');
});

test('manager can store a schedule note', function () {
    $manager = createManager();

    $response = $this->actingAs($manager)
        ->post(route('jadwal.notes.store'), [
            'tanggal' => '2026-06-09',
            'catatan' => 'Rapat koordinasi divisi keuangan',
            'tugas_id' => null
        ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Catatan berhasil ditambahkan.'
    ]);

    $this->assertDatabaseHas('catatan_jadwals', [
        'tanggal' => '2026-06-09',
        'catatan' => 'Rapat koordinasi divisi keuangan',
        'user_id' => $manager->id
    ]);
});

test('manager can update a schedule note', function () {
    $manager = createManager();

    $note = CatatanJadwal::create([
        'id' => (string) Str::ulid(),
        'tanggal' => '2026-06-09',
        'catatan' => 'Original Note',
        'user_id' => $manager->id
    ]);

    $response = $this->actingAs($manager)
        ->put(route('jadwal.notes.update', $note->id), [
            'catatan' => 'Updated Note',
            'tugas_id' => null
        ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Catatan berhasil diperbarui.'
    ]);

    $this->assertDatabaseHas('catatan_jadwals', [
        'id' => $note->id,
        'catatan' => 'Updated Note'
    ]);
});

test('manager can delete a schedule note', function () {
    $manager = createManager();

    $note = CatatanJadwal::create([
        'id' => (string) Str::ulid(),
        'tanggal' => '2026-06-09',
        'catatan' => 'Note to delete',
        'user_id' => $manager->id
    ]);

    $response = $this->actingAs($manager)
        ->delete(route('jadwal.notes.destroy', $note->id));

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Catatan berhasil dihapus.'
    ]);

    $this->assertDatabaseMissing('catatan_jadwals', [
        'id' => $note->id
    ]);
});
