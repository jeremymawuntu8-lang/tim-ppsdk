<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ba_was_alses', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_ba')->unique();
            $table->foreignId('pelaku_usaha_id')->constrained('pelaku_usahas');
            $table->string('nomor_pkkprl')->nullable();
            $table->date('tanggal_pengawasan');
            $table->string('tim_pengawas')->nullable();
            $table->text('lokasi')->nullable();
            $table->longText('hasil_pengawasan')->nullable();
            $table->longText('kesimpulan')->nullable();
            $table->longText('rekomendasi')->nullable();
            $table->enum('status', ['draft', 'proses', 'selesai', 'tindak_lanjut'])->default('draft');
            $table->string('file_ba_pdf')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ba_was_alses');
    }
};
