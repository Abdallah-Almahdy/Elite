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
        Schema::create('invice_printers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_printer_setting_id')->constrained('invice_printer_settings')->onDelete('cascade');
            $table->string('formName');
            $table->string('printerName');
            $table->string('permssionName')->unique();
            $table->string('numOfCopies')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invice_printers');
    }
};
