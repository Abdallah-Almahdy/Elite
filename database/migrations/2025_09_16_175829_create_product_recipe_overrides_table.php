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
        Schema::create('product_recipe_overrides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index('product_recipe_overrides_product_id_foreign');
            $table->unsignedBigInteger('ingredient_id')->index('product_recipe_overrides_ingredient_id_foreign');
            $table->decimal('custom_quantity', 10)->nullable();
            $table->boolean('is_removed')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_recipe_overrides');
    }
};
