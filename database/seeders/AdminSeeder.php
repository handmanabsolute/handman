<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id' => (string) Str::ulid(),
            'nama_lengkap' => 'Administrator Utama',
            'email' => 'handmanabsolute@gmail.com',
            'password' => Hash::make('admin123'),
            'no_telp' => '081234567890',
            'jenis_kelamin' => '-',
            'tanggal_lahir' => '2026-05-24',
            'nama_role' => 'admin',
        ]);
    }
}
