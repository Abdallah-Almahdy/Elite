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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('unit_id')->index('ingredients_unit_id_foreign');
            $table->unsignedBigInteger('recipe_id')->index('ingredients_recipe_id_foreign');
            $table->decimal('quantity_in_stock', 10)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->decimal('derived_quantity', 10)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
