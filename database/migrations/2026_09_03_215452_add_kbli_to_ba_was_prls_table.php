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
        Schema::table('ba_was_prls', function (Blueprint $table) {
            $table->string('kbli')->nullable()->after('jenis_usaha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ba_was_prls', function (Blueprint $table) {
            $table->dropColumn('kbli');
        });
    }
};
