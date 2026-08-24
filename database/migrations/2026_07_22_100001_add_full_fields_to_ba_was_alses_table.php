<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ba_was_alses', function (Blueprint $table) {
            $table->foreignId('pelaku_usaha_id')->nullable()->change();
            $table->foreignId('provinsi_id')->nullable()->after('pelaku_usaha_id')->constrained('provinsis')->nullOnDelete();
            $table->string('unit_kerja')->nullable()->default('Pangkalan PSDKP Bitung')->after('no_surat_tugas');
            $table->string('kategori_pengawasan')->nullable()->after('jam_wita');
            $table->string('objek_pengawasan')->nullable()->after('kategori_pengawasan');
            $table->string('no_identitas')->nullable()->after('nama_usaha');
            $table->text('alamat_kantor')->nullable()->after('no_identitas');
            $table->text('alamat_kegiatan')->nullable()->after('alamat_kantor');
            $table->string('nomor_nib')->nullable()->after('alamat_kegiatan');
            $table->string('jenis_kegiatan_usaha')->nullable()->after('nomor_nib');
            $table->string('penerbit_izin')->nullable()->after('jenis_kegiatan_usaha');
            $table->string('nomor_izin_alse')->nullable()->after('penerbit_izin');
            $table->date('tgl_terbit_izin_alse')->nullable()->after('nomor_izin_alse');
            $table->string('masa_berlaku_izin_alse')->nullable()->after('tgl_terbit_izin_alse');
            $table->string('nama_dokumen_lain')->nullable()->after('masa_berlaku_izin_alse');
            $table->string('nomor_dokumen_lain')->nullable()->after('nama_dokumen_lain');
            $table->string('kategori_kawasan')->nullable()->after('nomor_dokumen_lain');
            $table->string('judul_pemenuhan_ketentuan')->nullable()->after('kategori_kawasan');
            $table->string('debit_volume_air_laut')->nullable()->after('judul_pemenuhan_ketentuan');
            $table->string('kesesuaian_volume_air')->nullable()->after('debit_volume_air_laut');
            $table->string('kesesuaian_koordinat_inlet')->nullable()->after('kesesuaian_volume_air');
            $table->string('dugaan_pelanggaran')->nullable()->after('kesesuaian_koordinat_inlet');
            $table->text('penjelasan_dugaan_pelanggaran')->nullable()->after('dugaan_pelanggaran');
            $table->text('analisa_pengawasan')->nullable()->after('penjelasan_dugaan_pelanggaran');
            $table->string('ketua_tim_tanda_tangan')->nullable()->after('catatan_pengesahan');
            $table->string('pj_usaha_tanda_tangan')->nullable()->after('ketua_tim_tanda_tangan');
        });

        Schema::table('ba_was_alse_pengawas', function (Blueprint $table) {
            $table->string('unit_kerja')->nullable()->after('jabatan');
            $table->string('tanda_tangan')->nullable()->after('unit_kerja');
        });

        Schema::table('ba_was_alse_saksis', function (Blueprint $table) {
            $table->string('tanda_tangan')->nullable()->after('pekerjaan');
        });
    }

    public function down(): void
    {
        Schema::table('ba_was_alse_saksis', function (Blueprint $table) {
            $table->dropColumn(['tanda_tangan']);
        });

        Schema::table('ba_was_alse_pengawas', function (Blueprint $table) {
            $table->dropColumn(['unit_kerja', 'tanda_tangan']);
        });

        Schema::table('ba_was_alses', function (Blueprint $table) {
            $table->dropForeign(['provinsi_id']);
            $table->dropColumn([
                'provinsi_id', 'unit_kerja', 'kategori_pengawasan', 'objek_pengawasan',
                'no_identitas', 'alamat_kantor', 'alamat_kegiatan', 'nomor_nib',
                'jenis_kegiatan_usaha', 'penerbit_izin', 'nomor_izin_alse', 'tgl_terbit_izin_alse',
                'masa_berlaku_izin_alse', 'nama_dokumen_lain', 'nomor_dokumen_lain',
                'kategori_kawasan', 'judul_pemenuhan_ketentuan', 'debit_volume_air_laut',
                'kesesuaian_volume_air', 'kesesuaian_koordinat_inlet', 'dugaan_pelanggaran',
                'penjelasan_dugaan_pelanggaran', 'analisa_pengawasan', 'ketua_tim_tanda_tangan',
                'pj_usaha_tanda_tangan',
            ]);
        });
    }
};
