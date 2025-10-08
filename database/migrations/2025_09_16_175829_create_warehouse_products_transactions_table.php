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
        Schema::create('warehouse_products_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index('warehouse_products_transactions_product_id_foreign');
            $table->unsignedBigInteger('warehouse_id')->index('warehouse_products_transactions_warehouse_id_foreign');
            $table->unsignedBigInteger('target_warehouse_id')->index('warehouse_products_transactions_target_warehouse_id_foreign');
            $table->integer('quantity');
            $table->unsignedBigInteger('type_id')->index('warehouse_products_transactions_type_id_foreign');
            $table->unsignedBigInteger('user_id')->index('warehouse_products_transactions_user_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_products_transactions');
    }
};
