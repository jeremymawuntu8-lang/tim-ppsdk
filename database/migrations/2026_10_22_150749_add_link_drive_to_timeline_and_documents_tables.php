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
        // 1. Timeline Tahapan Files
        if (Schema::hasTable('timeline_tahapan_files')) {
            Schema::table('timeline_tahapan_files', function (Blueprint $table) {
                if (!Schema::hasColumn('timeline_tahapan_files', 'tipe')) {
                    $table->string('tipe', 20)->default('file')->after('kategori');
                }
                if (!Schema::hasColumn('timeline_tahapan_files', 'link_drive')) {
                    $table->text('link_drive')->nullable()->after('file_path');
                }
                $table->string('file_path')->nullable()->change();
            });
        }

        // 2. Company Documents
        if (Schema::hasTable('company_documents')) {
            Schema::table('company_documents', function (Blueprint $table) {
                if (!Schema::hasColumn('company_documents', 'tipe')) {
                    $table->string('tipe', 20)->default('file')->after('judul');
                }
                if (!Schema::hasColumn('company_documents', 'link_drive')) {
                    $table->text('link_drive')->nullable()->after('path_file');
                }
                $table->string('path_file')->nullable()->change();
                $table->string('nama_file')->nullable()->change();
            });
        }

        // 3. Dokumen Pelaku Usaha
        if (Schema::hasTable('dokumen_pelaku_usahas')) {
            Schema::table('dokumen_pelaku_usahas', function (Blueprint $table) {
                if (!Schema::hasColumn('dokumen_pelaku_usahas', 'tipe')) {
                    $table->string('tipe', 20)->default('file')->after('jenis_dokumen');
                }
                if (!Schema::hasColumn('dokumen_pelaku_usahas', 'link_drive')) {
                    $table->text('link_drive')->nullable()->after('path_file');
                }
                $table->string('path_file')->nullable()->change();
                $table->string('nama_file')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('timeline_tahapan_files')) {
            Schema::table('timeline_tahapan_files', function (Blueprint $table) {
                if (Schema::hasColumn('timeline_tahapan_files', 'link_drive')) {
                    $table->dropColumn('link_drive');
                }
                if (Schema::hasColumn('timeline_tahapan_files', 'tipe')) {
                    $table->dropColumn('tipe');
                }
            });
        }

        if (Schema::hasTable('company_documents')) {
            Schema::table('company_documents', function (Blueprint $table) {
                if (Schema::hasColumn('company_documents', 'link_drive')) {
                    $table->dropColumn('link_drive');
                }
                if (Schema::hasColumn('company_documents', 'tipe')) {
                    $table->dropColumn('tipe');
                }
            });
        }

        if (Schema::hasTable('dokumen_pelaku_usahas')) {
            Schema::table('dokumen_pelaku_usahas', function (Blueprint $table) {
                if (Schema::hasColumn('dokumen_pelaku_usahas', 'link_drive')) {
                    $table->dropColumn('link_drive');
                }
                if (Schema::hasColumn('dokumen_pelaku_usahas', 'tipe')) {
                    $table->dropColumn('tipe');
                }
            });
        }
    }
};
