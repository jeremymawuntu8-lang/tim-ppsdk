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
        Schema::create('pengawasan_tidak_langsung', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->nullable();
            $table->string('nama_unit_kerja')->nullable();
            $table->string('lokasi')->nullable();
            $table->foreignId('pelaku_usaha_id')->nullable()->constrained('pelaku_usahas')->nullOnDelete();
            $table->string('kegiatan_usaha')->nullable();
            $table->string('pelapor_sumber')->nullable();
            $table->date('tanggal_laporan')->nullable();
            $table->date('tanggal_telaah')->nullable();
            $table->text('hasil_telaah_penjelasan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->string('kepala_upt_nama')->nullable();
            $table->string('kepala_upt_ttd')->nullable();
            $table->enum('status', ['proses', 'selesai', 'tindak_lanjut'])->default('proses');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pengawasan_tidak_langsung_gambars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengawasan_tidak_langsung_id')->constrained('pengawasan_tidak_langsung', 'id', 'fk_ptl_gambars_ptl_id')->cascadeOnDelete();
            $table->string('kategori'); // 1-8 categories
            $table->string('path_file');
            $table->timestamps();
        });

        Schema::create('pengawasan_tidak_langsung_kesesuaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengawasan_tidak_langsung_id')->constrained('pengawasan_tidak_langsung', 'id', 'fk_ptl_kesesuaian_ptl_id')->cascadeOnDelete();
            $table->string('alokasi_ruang')->nullable();
            $table->string('kegiatan')->nullable();
            $table->enum('kesesuaian', ['diperbolehkan', 'tidak_diperbolehkan', 'diperbolehkan_dengan_izin'])->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengawasan_tidak_langsung_kesesuaian');
        Schema::dropIfExists('pengawasan_tidak_langsung_gambars');
        Schema::dropIfExists('pengawasan_tidak_langsung');
    }
};
