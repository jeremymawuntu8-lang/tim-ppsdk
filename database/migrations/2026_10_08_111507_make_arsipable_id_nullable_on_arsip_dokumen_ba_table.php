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
        Schema::table('arsip_dokumen_ba', function (Blueprint $table) {
            $table->unsignedBigInteger('arsipable_id')->nullable()->change();
            $table->string('arsipable_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip_dokumen_ba', function (Blueprint $table) {
            $table->unsignedBigInteger('arsipable_id')->nullable(false)->change();
            $table->string('arsipable_type')->nullable(false)->change();
        });
    }
};
