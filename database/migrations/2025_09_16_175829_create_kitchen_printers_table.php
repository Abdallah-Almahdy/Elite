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
        Schema::create('kitchen_printers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kitchen_id')->index('kitchen_printers_kitchen_id_foreign');
            $table->unsignedBigInteger('printer_id')->index('kitchen_printers_printer_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchen_printers');
    }
};
