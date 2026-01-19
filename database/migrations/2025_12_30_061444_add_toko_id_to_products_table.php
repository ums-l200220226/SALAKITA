<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('toko_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('tokos')
                  ->onDelete('cascade');

            $table->index('toko_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropIndex(['toko_id']);
            $table->dropColumn('toko_id');
        });
    }
};
