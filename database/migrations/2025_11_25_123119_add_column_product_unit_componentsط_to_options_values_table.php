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
        Schema::table('product_unit_components', function (Blueprint $table) {


            // $table->dropForeign(['product_unit_id']);
            // $table->dropForeign(['component_unit_id']);
            // $table->dropForeign(['product_id']);

            // // حذف الأعمدة القديمة (إذا احتاج الأمر)
            // $table->dropColumn(['product_unit_id', 'product_id', 'component_unit_id']);

            // // إنشاء الأعمدة الجديدة مع FK
            $table->foreignId('product_unit_id')->constrained('product_units')->cascadeOnDelete();
            $table->foreignId('component_unit_id')->constrained('product_units')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_unit_components', function (Blueprint $table) {
            //
        });
    }
};
