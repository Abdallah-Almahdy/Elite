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
        Schema::table('promo_code_users', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id'])->on('orders')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['promo_id'])->references(['id'])->on('promo_codes')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_code_users', function (Blueprint $table) {
            $table->dropForeign('promo_code_users_order_id_foreign');
            $table->dropForeign('promo_code_users_promo_id_foreign');
            $table->dropForeign('promo_code_users_user_id_foreign');
        });
    }
};
