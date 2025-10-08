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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->enum('promo_cat', ['user', 'all']);
            $table->enum('type', ['limited', 'unlimited'])->nullable();
            $table->integer('users_limit')->nullable();
            $table->integer('available_codes')->nullable();
            $table->integer('min_order_value')->nullable();
            $table->enum('discount_type', ['percentage', 'cash']);
            $table->decimal('discount_cash_value')->nullable();
            $table->tinyInteger('discount_percentage_value')->nullable();
            $table->boolean('active')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable()->index('promo_codes_user_id_foreign');
            $table->tinyInteger('check_offer_rate')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
