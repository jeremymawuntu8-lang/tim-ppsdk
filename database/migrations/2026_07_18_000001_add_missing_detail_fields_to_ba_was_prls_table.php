<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Melengkapi field-field yang belum ada di tabel ba_was_prls agar sesuai
     * dengan data form lengkap (lihat BA_WAS_PRL.zip & screenshot AppSheet) dan
     * struktur dokumen output (BA Inspeksi Lapangan, BA Pengawasan Pemanfaatan
     * Ruang Laut, Formulir Pengawasan Pemenuhan Dokumen KKPRL).
     */
    public function up(): void
    {
        Schema::table('ba_was_prls', function (Blueprint $table) {
            // --- Snapshot detail Pelaku Usaha (override, fallback ke relasi PelakuUsaha jika kosong) ---
            $table->string('jenis_usaha')->nullable()->after('nama_usaha');
            $table->string('luas_area')->nullable()->after('jenis_usaha');
            $table->foreignId('provinsi_id')->nullable()->after('luas_area')->constrained('provinsis')->nullOnDelete();
            $table->string('titik_koordinat_existing')->nullable()->after('titik_koordinat');

            // --- Detail Kesesuaian Kegiatan Pemanfaatan Ruang Laut (KKPRL) ---
            $table->string('kkprl_instansi_penerbit')->nullable()->after('tgl_terbit_pkkprl');
            $table->string('kkprl_masa_berlaku')->nullable()->after('kkprl_instansi_penerbit');

            // --- Detail Izin Pengelolaan ---
            $table->string('izin_pengelolaan_nomor')->nullable()->after('kkprl_masa_berlaku');
            $table->text('izin_pengelolaan_instansi_penerbit')->nullable()->after('izin_pengelolaan_nomor');
            $table->date('izin_pengelolaan_tanggal_penerbitan')->nullable()->after('izin_pengelolaan_instansi_penerbit');
            $table->string('izin_pengelolaan_masa_berlaku')->nullable()->after('izin_pengelolaan_tanggal_penerbitan');

            // --- Kesesuaian pelaksanaan kegiatan dengan dokumen perizinan (item 9b) ---
            $table->string('kesesuaian_izin_pengelolaan')->nullable()->after('izin_pengelolaan_masa_berlaku');

            // --- Formulir Pengawasan Pemenuhan Dokumen KKPRL (item 12 & 13) ---
            $table->string('penyampaian_laporan_tertulis')->nullable()->after('pemenuhan_kewajiban_pkkprl');
            $table->text('catatan_laporan_tahunan')->nullable()->after('penyampaian_laporan_tertulis');
            $table->string('dampak_pelaksanaan_pkkprl')->nullable()->after('catatan_laporan_tahunan');
            $table->text('catatan_dampak_prl')->nullable()->after('dampak_pelaksanaan_pkkprl');

            // --- Tanda tangan untuk pengesahan dokumen ---
            $table->string('ketua_tim_tanda_tangan')->nullable()->after('catatan_pengesahan');
            $table->string('pj_usaha_tanda_tangan')->nullable()->after('ketua_tim_tanda_tangan');
        });
    }

    public function down(): void
    {
        Schema::table('ba_was_prls', function (Blueprint $table) {
            $table->dropConstrainedForeignId('provinsi_id');
            $table->dropColumn([
                'jenis_usaha', 'luas_area', 'titik_koordinat_existing',
                'kkprl_instansi_penerbit', 'kkprl_masa_berlaku',
                'izin_pengelolaan_nomor', 'izin_pengelolaan_instansi_penerbit',
                'izin_pengelolaan_tanggal_penerbitan', 'izin_pengelolaan_masa_berlaku',
                'kesesuaian_izin_pengelolaan',
                'penyampaian_laporan_tertulis', 'catatan_laporan_tahunan',
                'dampak_pelaksanaan_pkkprl', 'catatan_dampak_prl',
                'ketua_tim_tanda_tangan', 'pj_usaha_tanda_tangan',
            ]);
        });
    }
};
