<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama_lengkap', 200)->nullable();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('no_telp', 20)->unique();
            $table->string('jenis_kelamin', 50);
            $table->date('tanggal_lahir');
            $table->string('foto_profil', 255)->nullable();
            $table->string('status_pegawai', 255)->nullable();
            $table->string('alamat', 300)->nullable();
            $table->enum('nama_role', ['admin', 'manager', 'staff']);
            $table->text('deskripsi_user')->nullable();
            $table->foreignUlid('departemen_id')->nullable()->constrained('departemens')->nullOnDelete();
            $table->string('otp_code', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUlid('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
