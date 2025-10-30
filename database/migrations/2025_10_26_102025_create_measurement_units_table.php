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
        // الوحدات العالميه
        Schema::create('measurement_units', function (Blueprint $table)
        {
            $table->id();
            $table->string('name');
            $table->foreignId('base_measurement_unit_id')
            ->nullable()
            ->constrained('measurement_units')
            ->cascadeOnDelete();
            $table->decimal('conversion_factor', 20, 6)->default(1);
            $table->boolean('is_base_unit')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_units');
    }
};
