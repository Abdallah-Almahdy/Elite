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
        Schema::create('phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phoneable_type');
            $table->unsignedBigInteger('phoneable_id');
            $table->string('number');
            $table->string('type')->default('mobile');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['phoneable_type', 'phoneable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
