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
        Schema::create('receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('receipts_user_id_foreign');
            $table->unsignedBigInteger('shift_id')->nullable()->index('receipts_shift_id_foreign');
            $table->decimal('total', 10);
            $table->decimal('paid_amount', 10);
            $table->enum('payment_method', ['cash', 'card', 'mixed'])->default('cash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
