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
        Schema::table('product_recipe_overrides', function (Blueprint $table) {
            $table->foreign(['ingredient_id'])->references(['id'])->on('ingredients')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_recipe_overrides', function (Blueprint $table) {
            $table->dropForeign('product_recipe_overrides_ingredient_id_foreign');
            $table->dropForeign('product_recipe_overrides_product_id_foreign');
        });
    }
};
