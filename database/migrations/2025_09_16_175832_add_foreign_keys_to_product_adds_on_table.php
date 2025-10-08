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
        Schema::table('product_adds_on', function (Blueprint $table) {
            $table->foreign(['adds_on_id'])->references(['id'])->on('adds_ons')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_adds_on', function (Blueprint $table) {
            $table->dropForeign('product_adds_on_adds_on_id_foreign');
            $table->dropForeign('product_adds_on_product_id_foreign');
        });
    }
};
