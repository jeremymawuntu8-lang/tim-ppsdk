<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ba_ppks', function (Blueprint $table) {
            $table->dropUnique('ba_ppks_nomor_ba_unique');
            $table->string('nomor_ba')->nullable()->change();
        });
        
        Schema::table('ba_was_alses', function (Blueprint $table) {
            $table->dropUnique('ba_was_alses_nomor_ba_unique');
            $table->string('nomor_ba')->nullable()->change();
        });
        
        Schema::table('ba_reklamasis', function (Blueprint $table) {
            $table->dropUnique('ba_reklamasis_nomor_ba_unique');
            $table->string('nomor_ba')->nullable()->change();
        });
    }

    public function down(): void
    {
        // down not implemented because we don't want to enforce unique constraint again if there are nulls/duplicates
    }
};
