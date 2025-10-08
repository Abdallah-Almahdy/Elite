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
        Schema::table('order_product_option_value', function (Blueprint $table) {
            $table->foreign(['option_value_id'])->references(['id'])->on('options_values')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['order_product_option_id'])->references(['id'])->on('order_product_options')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_product_option_value', function (Blueprint $table) {
            $table->dropForeign('order_product_option_value_option_value_id_foreign');
            $table->dropForeign('order_product_option_value_order_product_option_id_foreign');
        });
    }
};
