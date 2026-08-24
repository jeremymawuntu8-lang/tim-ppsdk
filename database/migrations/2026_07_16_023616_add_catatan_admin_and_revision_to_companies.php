<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->text('catatan_admin')->nullable()->after('rejection_reason');
            // Untuk MySQL, kita bisa ubah enum dengan cara ini:
            DB::statement("ALTER TABLE companies MODIFY COLUMN status ENUM('pending', 'revision', 'active', 'rejected') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('catatan_admin');
            DB::statement("ALTER TABLE companies MODIFY COLUMN status ENUM('pending', 'active', 'rejected') DEFAULT 'pending'");
        });
    }
};
