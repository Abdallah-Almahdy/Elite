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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('totalPrice', 10);
            $table->smallInteger('address');
            $table->text('phoneNumber');
            $table->tinyInteger('status')->comment('(1 pinding 2 Preparation 3 delivery 4 finished 5 canceld )');
            $table->timestamps();
            $table->tinyInteger('payment_method')->comment('0 credit 1, cash');
            $table->unsignedBigInteger('user_id')->index('orders_user_id_foreign');
            $table->unsignedBigInteger('promo_code_id')->nullable()->index('promo_code_id_foreign');
            $table->tinyInteger('order_type')->default(0)->comment('0 = delivery, 1 = takeaway, 2 = in-restaurant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
