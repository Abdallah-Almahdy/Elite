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
        Schema::table('ingredients', function (Blueprint $table) {
            $table->foreign(['recipe_id'])->references(['id'])->on('recipes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropForeign('ingredients_recipe_id_foreign');
            $table->dropForeign('ingredients_unit_id_foreign');
        });
    }
};
