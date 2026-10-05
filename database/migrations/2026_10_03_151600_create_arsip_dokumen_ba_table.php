<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip_dokumen_ba', function (Blueprint $table) {
            $table->id();
            $table->morphs('arsipable'); // arsipable_type + arsipable_id
            $table->enum('tipe', ['file', 'link'])->default('file');
            $table->string('judul');
            $table->text('keterangan')->nullable();
            $table->string('path_file')->nullable();
            $table->string('nama_file')->nullable();
            $table->string('link_gdrive', 2048)->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_dokumen_ba');
    }
};
