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
        Schema::create('invice_config', function (Blueprint $table) {
            $table->id();
            $table->string('printerName')->nullable();
            $table->string('password')->nullable();
            $table->string('taxValue')->nullable();
            $table->enum('defaultPaymentMethod', ['cash', 'credit_card', 'instapay', 'wallet', 'remaining'])->default('cash');
            $table->enum('defaultInvoiceType', ['take_away', 'hall','delvery'])->default('take_away');
            $table->boolean('applyTax')->default(0);
            $table->enum('taxTypes', ['%', 'pound'])->default('%');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invice_config');
    }
};
