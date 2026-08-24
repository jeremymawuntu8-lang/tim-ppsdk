<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ba_was_prls', function (Blueprint $table) {
            // Pengawas yang Bertugas
            $table->string('no_surat_tugas')->nullable()->after('nomor_ba');
            $table->string('ketua_tim_nama')->nullable()->after('no_surat_tugas');
            $table->string('ketua_tim_nip')->nullable()->after('ketua_tim_nama');
            $table->string('ketua_tim_jabatan')->nullable()->after('ketua_tim_nip');
            $table->string('ketua_tim_unit_kerja')->nullable()->after('ketua_tim_jabatan');

            // Informasi Pengawasan
            $table->string('jam_wita')->nullable()->after('tanggal_pengawasan');
            $table->string('nama_usaha')->nullable()->after('jam_wita');
            $table->string('titik_koordinat')->nullable()->after('longitude');

            // Form Pengawasan
            $table->string('metode_pengamatan')->nullable()->after('titik_koordinat');
            $table->string('nomor_perda_rzwp3k')->nullable()->after('metode_pengamatan');
            $table->date('tgl_terbit_pkkprl')->nullable()->after('nomor_pkkprl');
            $table->string('status_kesesuaian_kkprl')->nullable()->after('nomor_perda_rzwp3k');
            $table->text('catatan_dokumen_pkkprl')->nullable()->after('status_kesesuaian_kkprl');
            $table->string('pemenuhan_kewajiban_pkkprl')->nullable()->after('catatan_dokumen_pkkprl');

            // Informasi Pelaku Usaha
            $table->string('penanggung_jawab_usaha')->nullable()->after('pemenuhan_kewajiban_pkkprl');
            $table->string('jabatan_pj_usaha')->nullable()->after('penanggung_jawab_usaha');

            // Pengesahan
            $table->text('catatan_pengesahan')->nullable()->after('jabatan_pj_usaha');
        });
    }

    public function down(): void
    {
        Schema::table('ba_was_prls', function (Blueprint $table) {
            $table->dropColumn([
                'no_surat_tugas', 'ketua_tim_nama', 'ketua_tim_nip', 'ketua_tim_jabatan', 'ketua_tim_unit_kerja',
                'jam_wita', 'nama_usaha', 'titik_koordinat',
                'metode_pengamatan', 'nomor_perda_rzwp3k', 'tgl_terbit_pkkprl',
                'status_kesesuaian_kkprl', 'catatan_dokumen_pkkprl', 'pemenuhan_kewajiban_pkkprl',
                'penanggung_jawab_usaha', 'jabatan_pj_usaha', 'catatan_pengesahan',
            ]);
        });
    }
};
