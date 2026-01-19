<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // ID Order
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // ID Produk

            // Detail Item
            $table->string('product_name'); // Nama produk (snapshot saat beli)
            $table->decimal('price', 10, 2); // Harga satuan (snapshot saat beli)
            $table->integer('quantity'); // Jumlah yang dibeli
            $table->string('satuan'); // Satuan (kg/pcs/dll)
            $table->decimal('subtotal', 10, 2); // price x quantity

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
