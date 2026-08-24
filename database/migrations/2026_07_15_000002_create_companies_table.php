<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_perusahaan');
            $table->string('nib')->nullable();
            $table->string('npwp')->nullable();
            $table->text('alamat');
            $table->string('email_perusahaan')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('nama_penanggung_jawab');
            $table->string('jabatan_penanggung_jawab')->nullable();
            $table->string('logo')->nullable();
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
