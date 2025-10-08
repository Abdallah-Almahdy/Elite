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
        Schema::table('recipe_units', function (Blueprint $table) {
            $table->foreign(['recipe_id'])->references(['id'])->on('recipes')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipe_units', function (Blueprint $table) {
            $table->dropForeign('recipe_units_recipe_id_foreign');
            $table->dropForeign('recipe_units_unit_id_foreign');
        });
    }
};
