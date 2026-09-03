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
            $table->dropUnique('ba_was_prls_nomor_ba_unique');
            $table->string('nomor_ba')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ba_was_prls', function (Blueprint $table) {
            //
        });
    }
};
