<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ba_ppk_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ba_ppk_id')->constrained('ba_ppks')->cascadeOnDelete();
            $table->string('path_foto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ba_ppk_fotos');
    }
};
