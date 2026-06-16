<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Models\Departemen;
use App\Models\Tugas;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function setupSubmitData() {
    $departemen = Departemen::create([
        'nama_departemen' => 'Engineering',
        'deskripsi_departemen' => 'Engineering Department',
    ]);

    $manager = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Manager Engineering',
        'email'         => 'manager.eng@example.com',
        'password'      => Hash::make('Password123!'),
        'no_telp'       => '081233333333',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '1990-01-01',
        'nama_role'     => 'manager',
        'departemen_id' => $departemen->id,
        'is_active'     => 1,
    ]);

    $staff = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Staff Engineering',
        'email'         => 'staff.eng@example.com',
        'password'      => Hash::make('Password123!'),
        'no_telp'       => '081244444444',
        'jenis_kelamin' => 'P',
        'tanggal_lahir' => '1995-01-01',
        'nama_role'     => 'staff',
        'departemen_id' => $departemen->id,
        'is_active'     => 1,
    ]);

    $tugas = Tugas::create([
        'id'             => (string) Str::ulid(),
        'nama_tugas'     => 'Task Alpha',
        'deskripsi'      => 'First task description',
        'tanggal_tugas'  => '2026-06-14 09:00:00',
        'deadline_tugas' => '2026-06-15 17:00:00',
        'prioritas'      => 'Tinggi',
        'status_tugas'   => 'Belum Dikerjakan',
        'kategoritugas'  => 'Individu',
        'departemen_id'  => $departemen->id,
    ]);
    $tugas->detailTugas()->create(['user_id' => $staff->id]);

    return compact('manager', 'staff', 'tugas');
}

test('staff cannot submit task without any upload or link', function () {
    $data = setupSubmitData();

    $response = $this->actingAs($data['staff'])
        ->post(route('tugas.submit', $data['tugas']->id), []);

    $response->assertSessionHasErrors(['gambar_file']);
    
    // Assert status is still Belum Dikerjakan
    $this->assertEquals('Belum Dikerjakan', $data['tugas']->fresh()->status_tugas);
});

test('staff can submit task with link', function () {
    $data = setupSubmitData();

    $response = $this->actingAs($data['staff'])
        ->post(route('tugas.submit', $data['tugas']->id), [
            'link_tugas' => 'https://github.com/project',
        ]);

    $response->assertRedirect();
    
    $this->assertEquals('Menunggu Persetujuan', $data['tugas']->fresh()->status_tugas);
    $this->assertDatabaseHas('lampirans', [
        'link_tugas' => 'https://github.com/project',
    ]);
});

test('staff can submit task with image upload', function () {
    Storage::fake('public');
    $data = setupSubmitData();

    $file = UploadedFile::fake()->image('screenshot.png');

    $response = $this->actingAs($data['staff'])
        ->post(route('tugas.submit', $data['tugas']->id), [
            'gambar_file' => $file,
        ]);

    $response->assertRedirect();
    
    $this->assertEquals('Menunggu Persetujuan', $data['tugas']->fresh()->status_tugas);
    
    $freshTugas = $data['tugas']->fresh();
    $lampiran = $freshTugas->lampirans()->first();
    $this->assertNotNull($lampiran->gambar_file);
    Storage::disk('public')->assertExists($lampiran->gambar_file);
});
