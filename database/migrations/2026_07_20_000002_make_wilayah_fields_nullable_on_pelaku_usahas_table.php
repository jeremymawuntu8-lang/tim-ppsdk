<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mendukung pembuatan Pelaku Usaha secara cepat (auto-create) dari form
     * BA WAS PRL cukup dengan mengetik nama perusahaan — tanpa harus mengisi
     * jenis usaha & wilayah administratif terlebih dahulu. Detail-detail ini
     * bisa dilengkapi belakangan lewat menu Master Pelaku Usaha.
     */
    public function up(): void
    {
        Schema::table('pelaku_usahas', function (Blueprint $table) {
            $table->foreignId('jenis_usaha_id')->nullable()->change();
            $table->foreignId('provinsi_id')->nullable()->change();
            $table->foreignId('kabupaten_id')->nullable()->change();
            $table->foreignId('kecamatan_id')->nullable()->change();
            $table->foreignId('kelurahan_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pelaku_usahas', function (Blueprint $table) {
            $table->foreignId('jenis_usaha_id')->nullable(false)->change();
            $table->foreignId('provinsi_id')->nullable(false)->change();
            $table->foreignId('kabupaten_id')->nullable(false)->change();
            $table->foreignId('kecamatan_id')->nullable(false)->change();
            $table->foreignId('kelurahan_id')->nullable(false)->change();
        });
    }
};
