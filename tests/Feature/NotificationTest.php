<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

test('user can delete their notification successfully', function () {
    // 1. Create a user
    $user = User::create([
        'nama_lengkap'  => 'Staff One',
        'email'         => 'staff1@example.com',
        'password'      => Hash::make('Password123!'),
        'no_telp'       => '081234567890',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '1995-05-05',
        'nama_role'     => 'staff',
        'is_active'     => 1,
    ]);

    // 2. Create a notification for the user
    $notification = Notification::create([
        'id'        => (string) Str::ulid(),
        'user_id'   => $user->id,
        'title'     => 'Tugas Baru',
        'message'   => 'Anda menerima tugas baru',
        'type'      => 'tugas_baru',
        'is_read'   => false,
    ]);

    // 3. Make sure it exists in database
    $this->assertDatabaseHas('notifications', [
        'id' => $notification->id,
    ]);

    // 4. Send DELETE request to delete the notification
    $response = $this->actingAs($user)
        ->delete(route('notifications.destroy', $notification->id));

    // 5. Assert redirect and success
    $response->assertRedirect();
    
    // 6. Assert notification is deleted from database
    $this->assertDatabaseMissing('notifications', [
        'id' => $notification->id,
    ]);
});
