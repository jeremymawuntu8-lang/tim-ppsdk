<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ba_was_prl_saksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ba_was_prl_id')->constrained('ba_was_prls')->cascadeOnDelete();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ba_was_prl_saksis');
    }
};
