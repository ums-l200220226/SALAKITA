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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users (petani)
            $table->string('nama_produk');
            $table->enum('kategori', ['sayuran', 'buah', 'beras', 'rempah', 'lainnya'])->default('lainnya');
            $table->decimal('harga', 10, 2); // Harga dengan 2 desimal, max 10 digit
            $table->string('satuan')->default('kg'); // kg, ikat, pcs, dll
            $table->integer('stok')->default(0); // Stok tersedia
            $table->text('deskripsi')->nullable(); // Deskripsi produk (optional)
            $table->string('gambar')->nullable(); // Path gambar produk
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); // Status jual
            $table->integer('total_terjual')->default(0); // Tracking penjualan
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};