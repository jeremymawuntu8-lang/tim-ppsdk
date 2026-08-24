<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaku_usahas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('nomor_pkkprl')->unique()->nullable();
            $table->foreignId('jenis_usaha_id')->constrained('jenis_usahas');
            $table->decimal('luas_pkkprl', 15, 2)->nullable()->comment('dalam m2');
            $table->foreignId('provinsi_id')->constrained('provinsis');
            $table->foreignId('kabupaten_id')->constrained('kabupatens');
            $table->foreignId('kecamatan_id')->constrained('kecamatans');
            $table->foreignId('kelurahan_id')->constrained('kelurahans');
            $table->text('alamat')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('nama_pic')->nullable();
            $table->string('jabatan_pic')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif', 'dalam_proses', 'bermasalah'])->default('aktif');
            $table->string('foto_lokasi')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['provinsi_id', 'kabupaten_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaku_usahas');
    }
};
