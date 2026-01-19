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
        Schema::create('landing_page_settings', function (Blueprint $table) {
            $table->id();
            
            // Hero Section
            $table->string('hero_title')->default('SalaKita');
            $table->text('hero_description')->default('Platform agribisnis digital yang menghubungkan petani salak dengan pembeli di seluruh Indonesia');
            $table->string('hero_image')->nullable();
            $table->string('logo_image')->nullable();
            
            // Features Section
            $table->string('features_title')->default('Kenapa Memilih SalaKita?');
            
            // History Section
            $table->string('history_title')->default('Salak di Desa Panca Tunggal?');
            $table->text('history_content')->nullable();
            
            // Products Section
            $table->string('products_title')->default('Produk Terlaris Kami');
            $table->text('products_description')->default('Salak pilihan berkualitas tinggi langsung dari petani lokal');
            $table->boolean('show_products_section')->default(true);
            
            // Stats
            $table->integer('stats_transactions')->default(100);
            $table->integer('stats_products')->default(50);
            $table->decimal('stats_rating', 2, 1)->default(4.8);
            
            // Location Section
            $table->string('location_title')->default('Lokasi Kami');
            $table->text('location_address')->nullable();
            $table->string('location_phone')->nullable();
            $table->string('location_email')->nullable();
            $table->text('location_hours')->nullable();
            $table->text('location_map_url')->nullable();
            
            // CTA Section
            $table->string('cta_title')->default('Siap Mulai Belanja?');
            $table->text('cta_description')->default('Bergabunglah dengan ribuan pengguna yang sudah merasakan kemudahan berbelanja salak segar');
            
            // Footer
            $table->string('footer_copyright')->default('2024 SalaKita - Platform Agribisnis Digital');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_settings');
    }
};