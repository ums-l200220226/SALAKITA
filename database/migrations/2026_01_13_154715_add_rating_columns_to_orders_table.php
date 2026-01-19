<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('rating')->nullable()->after('status'); // 1-5
            $table->text('review')->nullable()->after('rating');
            $table->timestamp('reviewed_at')->nullable()->after('review');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['rating', 'review', 'reviewed_at']);
        });
    }
};
