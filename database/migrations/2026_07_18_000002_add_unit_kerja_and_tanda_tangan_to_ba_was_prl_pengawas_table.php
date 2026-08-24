<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan Unit Kerja (sesuai kolom "Unit Kerja" pada tabel tim pemeriksa
     * di dokumen BA) dan Tanda Tangan (untuk pengesahan dokumen) per anggota
     * pengawas.
     */
    public function up(): void
    {
        Schema::table('ba_was_prl_pengawas', function (Blueprint $table) {
            $table->string('unit_kerja')->nullable()->after('jabatan');
            $table->string('tanda_tangan')->nullable()->after('unit_kerja');
        });
    }

    public function down(): void
    {
        Schema::table('ba_was_prl_pengawas', function (Blueprint $table) {
            $table->dropColumn(['unit_kerja', 'tanda_tangan']);
        });
    }
};
