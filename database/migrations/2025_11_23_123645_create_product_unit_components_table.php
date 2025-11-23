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
        Schema::create('product_unit_components', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_unit_id')->constrained('product_units')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('NO ACTION');
            $table->foreignId('component_unit_id')->constrained('product_units')->onDelete('NO ACTION');
            $table->decimal('quantity', 50, 2);
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_uint_components');
    }
};
