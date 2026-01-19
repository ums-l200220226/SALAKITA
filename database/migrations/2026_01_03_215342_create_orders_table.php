<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Nomor pesanan unik (misal: ORD-20250103-001)

            // Relasi
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pembeli
            $table->foreignId('toko_id')->nullable()->constrained('tokos')->onDelete('set null'); // Toko penjual

            // Info Pengiriman
            $table->enum('metode_penerimaan', ['diambil', 'dikirim']); // Diambil/Dikirim
            $table->char('province_id', 2)->nullable(); // ID Provinsi (dari laravolt/indonesia)
            $table->char('city_id', 4)->nullable(); // ID Kota (dari laravolt/indonesia)
            $table->text('alamat_lengkap'); // Alamat detail pembeli
            $table->decimal('ongkir', 10, 2)->default(0); // Ongkos kirim

            // Info Pembayaran
            $table->enum('metode_pembayaran', ['cod', 'qris'])->default('cod'); // COD/Transfer
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed'])->default('pending');

            // Info Pesanan
            $table->decimal('subtotal', 10, 2); // Total harga produk
            $table->decimal('total', 10, 2); // Subtotal + Ongkir
            $table->text('catatan')->nullable(); // Catatan dari pembeli

            // Status Pesanan
            $table->enum('status', ['pending', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
