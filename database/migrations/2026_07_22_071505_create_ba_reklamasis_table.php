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
        Schema::create('ba_reklamasis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_ba')->unique();
            $table->foreignId('pelaku_usaha_id')->constrained('pelaku_usahas');
            $table->date('tanggal_pengawasan');
            $table->time('jam_wita')->nullable();
            
            // Detail Penanggung Jawab & Usaha
            $table->string('penanggung_jawab_usaha')->nullable();
            $table->string('nik_pj')->nullable();
            $table->text('alamat_pj')->nullable();
            $table->string('pelaksana_reklamasi')->nullable();
            $table->text('lokasi_reklamasi')->nullable();
            $table->string('jenis_pemanfaatan_reklamasi')->nullable();
            
            // Dokumen 1: KKPRL
            $table->string('kkprl_nomor_izin')->nullable();
            $table->date('kkprl_terbit_izin')->nullable();
            $table->string('kkprl_pemberi_izin')->nullable();
            $table->string('kkprl_peruntukan')->nullable();
            
            // Dokumen 2: Izin Reklamasi
            $table->string('izin_reklamasi_nomor')->nullable();
            $table->date('izin_reklamasi_terbit')->nullable();
            $table->string('izin_reklamasi_pemberi')->nullable();
            $table->string('izin_reklamasi_peruntukan')->nullable();
            
            // Dokumen 3: Izin Lainnya
            $table->string('izin_lainnya_nomor')->nullable();
            $table->date('izin_lainnya_terbit')->nullable();
            $table->string('izin_lainnya_pemberi')->nullable();
            $table->string('izin_lainnya_peruntukan')->nullable();
            
            // Status & File
            $table->enum('status', ['draft', 'proses', 'selesai', 'tindak_lanjut'])->default('draft');
            $table->string('file_ba_pdf')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            // Signatures (Paths to image files)
            $table->string('ttd_pelaku_usaha')->nullable();
            $table->string('ttd_pengawas_1')->nullable();
            $table->string('ttd_pengawas_2')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ba_reklamasis');
    }
};
