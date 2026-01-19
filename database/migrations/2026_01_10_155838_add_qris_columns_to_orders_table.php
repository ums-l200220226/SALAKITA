<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('qris_id')->nullable();
            $table->text('qris_url')->nullable();
            $table->string('qris_status')->default('pending');
            $table->timestamp('qris_expired_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['qris_id', 'qris_url', 'qris_status', 'qris_expired_at']);
        });
    }
};
