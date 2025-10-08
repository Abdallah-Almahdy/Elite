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
        Schema::table('receipt_items', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['product_option_id'])->references(['id'])->on('product_options')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['receipt_id'])->references(['id'])->on('receipts')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_items', function (Blueprint $table) {
            $table->dropForeign('receipt_items_product_id_foreign');
            $table->dropForeign('receipt_items_product_option_id_foreign');
            $table->dropForeign('receipt_items_receipt_id_foreign');
        });
    }
};
