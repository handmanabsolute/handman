<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lampirans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->string('nama_file', 255)->nullable();
            $table->string('gambar_file', 255)->nullable();
            $table->text('link_tugas')->nullable();
            $table->string('keterangan_tugas', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampirans');
    }
};
