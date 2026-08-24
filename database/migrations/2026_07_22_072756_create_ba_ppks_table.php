<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ba_ppks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_ba')->unique();
            $table->foreignId('pelaku_usaha_id')->nullable()->constrained('pelaku_usahas')->nullOnDelete();
            
            // Header
            $table->string('unit_kerja')->nullable();
            $table->date('tanggal_pengawasan');
            $table->time('jam_wita')->nullable();
            $table->string('lokasi')->nullable();

            // 1. Profil
            $table->string('nama_pj')->nullable();
            $table->string('nik_pj')->nullable();
            $table->text('alamat_pj')->nullable();
            $table->string('status_modal')->nullable(); // asing/dalam_negeri
            $table->string('kepemilikan_saham')->nullable(); // swasta/pemerintah
            $table->string('nama_saham_1')->nullable();
            $table->string('nama_saham_2')->nullable();
            $table->string('nama_pulau')->nullable();
            $table->string('kategori_lokasi')->nullable(); // ppk/ppkt
            $table->json('jenis_usaha')->nullable(); // checkbox array

            // 2. Pemeriksaan Perizinan (Syarat wajib Rekomendasi PPK)
            $table->boolean('syarat_rdtr_belum')->nullable();
            $table->boolean('syarat_rdtr_non_oss')->nullable();
            $table->boolean('syarat_rtr_zonasi')->nullable();
            $table->boolean('syarat_pengecualian_pkkpr')->nullable();

            // 2.1 Rekomendasi PPK
            $table->boolean('rek_ppk_ada')->nullable();
            $table->string('rek_ppk_jenis')->nullable(); // <100 / pma
            $table->string('rek_ppk_jenis_sts')->nullable(); // S/TS
            $table->string('rek_ppk_nomor')->nullable();
            $table->string('rek_ppk_nomor_sts')->nullable();
            $table->date('rek_ppk_tgl')->nullable();
            $table->string('rek_ppk_tgl_sts')->nullable();
            $table->string('rek_ppk_penerbit')->nullable();
            $table->string('rek_ppk_penerbit_sts')->nullable();
            $table->string('rek_ppk_masa_berlaku')->nullable();
            $table->string('rek_ppk_masa_berlaku_sts')->nullable();
            $table->string('rek_ppk_jenis_kegiatan')->nullable();
            $table->string('rek_ppk_jenis_kegiatan_sts')->nullable();
            $table->string('rek_ppk_luas_izin')->nullable();
            $table->string('rek_ppk_luas_izin_sts')->nullable();
            $table->string('rek_ppk_luas_pemanfaatan')->nullable();
            $table->text('rek_ppk_koordinat_izin')->nullable();
            $table->string('rek_ppk_koordinat_izin_sts')->nullable();
            $table->text('rek_ppk_koordinat_eksisting')->nullable();

            // 2.2 PKKPR
            $table->boolean('pkkpr_ada')->nullable();
            $table->string('pkkpr_nomor')->nullable();
            $table->date('pkkpr_tgl')->nullable();
            $table->string('pkkpr_penerbit')->nullable();
            $table->string('pkkpr_luas')->nullable();
            $table->text('pkkpr_koordinat')->nullable();

            // 2.3 PERSETUJUAN LINGKUNGAN
            $table->boolean('lingkungan_ada')->nullable();
            $table->string('lingkungan_nomor')->nullable();
            $table->date('lingkungan_tgl')->nullable();
            $table->string('lingkungan_penerbit')->nullable();

            // 2.4 NIB
            $table->boolean('nib_ada')->nullable();
            $table->string('nib_nomor')->nullable();
            $table->date('nib_tgl')->nullable();
            $table->string('nib_kbli')->nullable();

            // 2.5 PERIZINAN BERUSAHA
            $table->boolean('izin_usaha_ada')->nullable();
            $table->string('izin_usaha_nomor')->nullable();
            $table->date('izin_usaha_tgl')->nullable();
            $table->string('izin_usaha_penerbit')->nullable();
            $table->string('izin_usaha_masa')->nullable();
            $table->string('izin_usaha_jenis')->nullable();
            $table->string('izin_usaha_luas')->nullable();
            $table->string('izin_usaha_lokasi')->nullable();
            $table->text('izin_usaha_koordinat')->nullable();

            // 2.6 DOKUMEN LAINNYA
            $table->boolean('dok_lain_ada')->nullable();
            $table->string('dok_lain_jenis')->nullable();
            $table->string('dok_lain_nomor')->nullable();
            $table->date('dok_lain_tgl')->nullable();
            $table->string('dok_lain_penerbit')->nullable();
            $table->string('dok_lain_lokasi')->nullable();

            // 3. Pemeriksaan pemenuhan ketentuan
            $table->string('pemenuhan_rth')->nullable(); // S/TS
            $table->string('pemenuhan_rtr')->nullable();
            $table->string('pemenuhan_akses')->nullable();
            $table->string('pemenuhan_jenis')->nullable();

            // 4 & 5 Dugaan
            $table->boolean('dugaan_pelanggaran_ada')->nullable();
            $table->text('dugaan_pelanggaran_ket')->nullable();
            $table->boolean('dugaan_kerusakan_ada')->nullable();
            $table->text('dugaan_kerusakan_ket')->nullable();

            // 6 & 7 Kesimpulan dan Rekomendasi
            $table->text('kesimpulan')->nullable();
            $table->json('rekomendasi_tindakan')->nullable(); // array checkbox
            $table->text('rekomendasi_lainnya')->nullable();

            // TTD
            $table->string('ttd_pelaku_usaha')->nullable();
            $table->string('ttd_pengawas_1')->nullable(); // Untuk 1 orang perwakilan polsus

            // Sistem
            $table->string('status')->default('draft');
            $table->string('file_ba_pdf')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ba_ppks');
    }
};
