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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('nomor_pengajuan')->nullable()->after('id')->unique();
            $table->date('tanggal')->nullable()->after('nama_perusahaan');
            $table->text('dokumen_diunggah')->nullable()->after('jabatan_penanggung_jawab');
            $table->string('file_dokumen')->nullable()->after('dokumen_diunggah');
            $table->text('keterangan_tambahan')->nullable()->after('file_dokumen');

            // Jadikan field yang tidak lagi digunakan di form menjadi nullable
            $table->text('alamat')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_pengajuan',
                'tanggal',
                'dokumen_diunggah',
                'file_dokumen',
                'keterangan_tambahan'
            ]);
            
            // Revert alamat menjadi tidak nullable (harus hati-hati jika ada data kosong)
            // $table->text('alamat')->nullable(false)->change();
        });
    }
};
