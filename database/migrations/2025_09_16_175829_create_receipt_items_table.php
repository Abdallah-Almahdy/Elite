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
        Schema::create('receipt_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receipt_id')->index('receipt_items_receipt_id_foreign');
            $table->unsignedBigInteger('product_id')->index('receipt_items_product_id_foreign');
            $table->unsignedBigInteger('product_option_id')->nullable()->index('receipt_items_product_option_id_foreign');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10);
            $table->decimal('total', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_items');
    }
};
