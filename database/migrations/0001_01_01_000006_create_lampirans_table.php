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
        Schema::create('lampirans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tugas_id')->constrained('tugas')->cascadeOnDelete();
            $table->string('nama_file', 255)->nullable();
            $table->text('link_tugas')->nullable();
            $table->string('jenis', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampirans');
    }
};
