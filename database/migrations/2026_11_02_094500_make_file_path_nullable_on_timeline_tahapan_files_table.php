<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('timeline_tahapan_files')) {
            try {
                DB::statement("ALTER TABLE timeline_tahapan_files MODIFY file_path VARCHAR(255) NULL DEFAULT NULL");
            } catch (\Throwable $e) {
                // Abaikan jika alter gagal atau engine berbeda
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
