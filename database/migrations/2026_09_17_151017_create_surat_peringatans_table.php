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
        Schema::create('surat_peringatans', function (Blueprint $table) {
            $table->id();
            
            // 1. Data Izin & Perusahaan
            $table->string('id_penerbitan')->nullable();
            $table->foreignId('pelaku_usaha_id')->nullable()->constrained('pelaku_usahas')->nullOnDelete();
            $table->string('contact_person')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jenis_permohonan')->nullable();
            $table->string('status_berusaha')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('nama_perairan')->nullable();
            $table->string('upt')->nullable();
            $table->text('detil_kegiatan')->nullable();
            $table->double('luas')->nullable(); // Ha / Unit
            $table->double('panjang')->nullable(); // km
            $table->string('nomor_kkprl')->nullable();
            $table->date('tanggal_penerbitan')->nullable();
            
            // 2. Laporan KKPRL
            $table->date('laporan_1_tgl')->nullable();
            $table->string('laporan_1_status')->nullable();
            $table->date('laporan_2_tgl')->nullable();
            $table->string('laporan_2_status')->nullable();
            $table->date('laporan_3_tgl')->nullable();
            $table->string('laporan_3_status')->nullable();
            $table->date('laporan_4_tgl')->nullable();
            $table->string('laporan_4_status')->nullable();
            $table->date('laporan_5_tgl')->nullable();
            $table->string('laporan_5_status')->nullable();
            
            // 3. Sanksi Keseluruhan / Status Akhir
            $table->string('sanksi')->nullable();
            
            // 4. SP 1 Data
            $table->text('sp1_keterangan')->nullable();
            $table->string('sp1_nomor_surat_tgl')->nullable();
            $table->string('sp1_status_terkirim')->nullable();
            $table->string('sp1_link_upload')->nullable();
            
            // 5. SP 2 Data
            $table->text('sp2_keterangan')->nullable();
            $table->string('sp2_nomor_surat_tgl')->nullable();
            $table->string('sp2_status_terkirim')->nullable();
            $table->string('sp2_link_upload')->nullable();
            
            // 6. SP 3 Data
            $table->text('sp3_keterangan')->nullable();
            $table->string('sp3_nomor_surat_tgl')->nullable();
            $table->string('sp3_status_terkirim')->nullable();
            $table->string('sp3_link_upload')->nullable();
            
            // 7. Data Tambahan
            $table->string('pemutakhiran')->nullable();
            $table->string('link_dokumen_laporan_tahunan')->nullable();
            $table->string('link_dokumen_kkprl')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_peringatans');
    }
};
