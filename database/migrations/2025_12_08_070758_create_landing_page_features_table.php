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
        Schema::create('landing_page_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_page_setting_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('icon_type')->default('svg'); // svg atau image
            $table->text('icon_svg')->nullable(); // SVG code
            $table->string('icon_image')->nullable(); // Path gambar
            $table->integer('order')->default(0); // Urutan tampil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_features');
    }
};