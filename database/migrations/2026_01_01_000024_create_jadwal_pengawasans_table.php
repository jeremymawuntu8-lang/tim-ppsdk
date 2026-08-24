<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pengawasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelaku_usaha_id')->constrained('pelaku_usahas');
            $table->enum('jenis_pengawasan', ['prl', 'alse'])->default('prl');
            $table->date('tanggal_rencana');
            $table->string('tim_pengawas')->nullable();
            $table->enum('status', ['belum_dilaksanakan', 'sedang_berjalan', 'selesai', 'dibatalkan'])->default('belum_dilaksanakan');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengawasans');
    }
};
