<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catatan_jadwals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->date('tanggal');
            $table->text('catatan');
            $table->foreignUlid('tugas_id')->nullable()->constrained('tugas')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_jadwals');
    }
};
