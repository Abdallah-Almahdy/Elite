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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->boolean('uses_recipe')->default(false);
            $table->float('price', 10);
            $table->longText('description')->nullable();
            $table->longText('photo')->nullable();
            $table->unsignedBigInteger('section_id')->nullable()->index('products_section_id_foreign');
            $table->unsignedBigInteger('company_id')->nullable()->index('products_company_id_foreign');
            $table->tinyInteger('active')->default(0);
            $table->integer('qnt')->nullable()->default(100);
            $table->timestamps();
            $table->integer('purchase_count')->default(0);
            $table->integer('offer_rate')->default(0);
            $table->text('bar_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
