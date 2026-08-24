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
        Schema::create('ba_reklamasi_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ba_reklamasi_id')->constrained('ba_reklamasis')->onDelete('cascade');
            $table->string('path_foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ba_reklamasi_fotos');
    }
};
