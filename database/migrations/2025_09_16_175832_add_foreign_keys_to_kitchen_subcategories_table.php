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
        Schema::table('kitchen_subcategories', function (Blueprint $table) {
            $table->foreign(['category_id'])->references(['id'])->on('sub_sections')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['kitchen_id'])->references(['id'])->on('kitchen')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kitchen_subcategories', function (Blueprint $table) {
            $table->dropForeign('kitchen_subcategories_category_id_foreign');
            $table->dropForeign('kitchen_subcategories_kitchen_id_foreign');
        });
    }
};
