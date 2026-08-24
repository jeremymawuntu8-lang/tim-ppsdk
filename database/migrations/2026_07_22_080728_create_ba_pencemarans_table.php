<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ba_pencemarans', function (Blueprint $table) {
            $table->id();
            
            // Tab 1: Utama
            $table->string('nomor_ba')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->date('tanggal_pengawasan')->nullable();
            $table->string('jam_wita')->nullable();
            $table->enum('jenis_pengawasan', ['rutin', 'insidental'])->nullable();
            $table->string('laporan_pengaduan_nomor')->nullable();
            $table->date('laporan_pengaduan_tgl')->nullable();
            $table->text('lokasi_pengawasan')->nullable();
            $table->text('koordinat')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            // Tab 2: Profil Usaha
            $table->foreignId('pelaku_usaha_id')->nullable()->constrained('pelaku_usahas')->nullOnDelete();
            $table->string('nama_usaha_kegiatan')->nullable();
            $table->string('nib')->nullable();
            $table->string('luas_darat')->nullable();
            $table->string('luas_laut')->nullable();
            $table->string('zona_sub_zona')->nullable();
            
            $table->string('nama_pj')->nullable();
            $table->string('nik_pj')->nullable();
            $table->string('jabatan_pj')->nullable();
            $table->text('alamat_kantor')->nullable();
            $table->string('email_pj')->nullable();
            $table->string('no_telp_pj')->nullable();

            // Tab 3: Sektor & Izin
            $table->json('jenis_usaha')->nullable();
            $table->json('perizinan_dasar')->nullable();
            $table->json('dokumen_pencegahan')->nullable();
            $table->json('perizinan_berusaha')->nullable();

            // Tab 4: Hasil Pengawasan
            $table->json('hasil_pengawasan')->nullable();

            // Tab 5: Dugaan & Sampel
            $table->boolean('dugaan_pencemaran_ada')->default(false);
            $table->text('dugaan_pencemaran_ket')->nullable();
            $table->string('luas_area_tercemar')->nullable();
            $table->string('luas_mangrove')->nullable();
            $table->string('luas_lamun')->nullable();
            $table->string('luas_terumbu_karang')->nullable();
            $table->string('luas_habitat_ikan')->nullable();

            $table->json('indikasi_ketidakpatuhan')->nullable(); // (menghentikan, memaksa, penyegelan, dll)

            $table->boolean('sampel_ada')->default(false);
            $table->date('sampel_tgl')->nullable();
            $table->integer('sampel_jumlah_titik')->nullable();
            $table->text('sampel_koordinat')->nullable();
            $table->string('sampel_nama_lab')->nullable();
            $table->date('sampel_lab_tgl')->nullable();
            $table->enum('sampel_hasil_uji', ['melampaui', 'di_bawah'])->nullable();
            
            $table->text('kronologis')->nullable();

            // Tab 6: Kesimpulan & TTD
            $table->enum('kesimpulan_dokumen', ['sesuai', 'tidak_sesuai'])->nullable();
            $table->boolean('kesimpulan_indikasi_pencemaran')->nullable();
            $table->boolean('kesimpulan_indikasi_pelanggaran')->nullable();
            $table->text('kesimpulan_keterangan')->nullable();

            $table->text('ttd_pelaku_usaha')->nullable();
            $table->text('ttd_pengawas_1')->nullable();
            $table->text('ttd_saksi_1')->nullable();
            $table->text('ttd_saksi_2')->nullable();

            // Lampiran Form E
            $table->json('lampiran_e1')->nullable();
            $table->json('lampiran_e2')->nullable();
            $table->json('lampiran_e3')->nullable();
            $table->json('lampiran_e4')->nullable();
            $table->json('lampiran_e5')->nullable();
            $table->json('lampiran_e6')->nullable();

            $table->string('status')->default('draft');
            $table->string('file_ba_pdf')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ba_pencemarans');
    }
};
