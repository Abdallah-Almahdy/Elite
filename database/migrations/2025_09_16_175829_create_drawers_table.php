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
        Schema::create('drawers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cashier_id')->index('drawers_cashier_id_foreign');
            $table->unsignedBigInteger('safe_id')->nullable()->index('drawers_safe_id_foreign');
            $table->decimal('cash_amount', 10)->default(0);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drawers');
    }
};
