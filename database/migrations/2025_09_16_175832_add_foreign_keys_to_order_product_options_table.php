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
        Schema::table('order_product_options', function (Blueprint $table) {
            $table->foreign(['option_id'])->references(['id'])->on('options')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['order_product_id'])->references(['id'])->on('order_products')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_product_options', function (Blueprint $table) {
            $table->dropForeign('order_product_options_option_id_foreign');
            $table->dropForeign('order_product_options_order_product_id_foreign');
        });
    }
};
