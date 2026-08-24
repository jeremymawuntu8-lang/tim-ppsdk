<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ba_was_prl_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ba_was_prl_id')->constrained('ba_was_prls')->cascadeOnDelete();
            $table->string('path_foto');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ba_was_prl_fotos');
    }
};
