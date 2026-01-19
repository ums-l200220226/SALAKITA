<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_toko');
            $table->text('deskripsi_toko')->nullable();
            $table->string('logo_toko')->nullable();
            $table->text('alamat_toko')->nullable();
            $table->string('no_telp_toko', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('petani_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tokos');
    }
};
