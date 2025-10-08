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
        Schema::create('banares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('path');
            $table->timestamps();
            $table->integer('main_sec_id')->nullable();
            $table->integer('sub_sec_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->boolean('click')->nullable();
            $table->boolean('offers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banares');
    }
};
