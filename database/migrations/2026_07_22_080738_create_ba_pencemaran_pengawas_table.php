<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ba_pencemaran_pengawas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ba_pencemaran_id')->constrained('ba_pencemarans')->cascadeOnDelete();
            $table->string('nama')->nullable();
            $table->string('nip')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ba_pencemaran_pengawas');
    }
};
