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
        Schema::create('timeline_tahapan_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timeline_tahapan_id')->constrained('timeline_tahapans')->cascadeOnDelete();
            $table->string('nama_dokumen');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeline_tahapan_files');
    }
};
