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
        Schema::table('timeline_tahapan_files', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('nama_dokumen'); // bahan_paparan, permohonan_supervisi, peta_spasial, dokumen_tambahan
            $table->unsignedTinyInteger('slot')->nullable()->after('kategori'); // 1-8 untuk peta spasial
            $table->string('file_name')->nullable()->after('file_path');
            $table->unsignedBigInteger('file_size')->nullable()->after('file_name');
            $table->string('mime_type')->nullable()->after('file_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timeline_tahapan_files', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'slot', 'file_name', 'file_size', 'mime_type']);
        });
    }
};
