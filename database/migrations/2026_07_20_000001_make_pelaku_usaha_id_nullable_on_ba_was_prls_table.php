<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pelaku Usaha kini boleh diketik manual (nama usaha baru yang belum ada di
     * master data pelaku_usahas) lewat field "nama_usaha", tanpa harus memilih
     * salah satu Pelaku Usaha yang sudah terdaftar lebih dulu. Jadi pelaku_usaha_id
     * perlu dibuat nullable.
     */
    public function up(): void
    {
        Schema::table('ba_was_prls', function (Blueprint $table) {
            $table->foreignId('pelaku_usaha_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ba_was_prls', function (Blueprint $table) {
            $table->foreignId('pelaku_usaha_id')->nullable(false)->change();
        });
    }
};
