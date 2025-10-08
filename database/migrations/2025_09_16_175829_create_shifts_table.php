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
        Schema::create('shifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cashier_id')->index('shifts_cashier_id_foreign');
            $table->decimal('start_cash', 10);
            $table->decimal('end_cash', 10)->nullable();
            $table->timestamp('start_time')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('end_time')->nullable();
            $table->unsignedBigInteger('safe_id')->nullable()->index('shifts_safe_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
