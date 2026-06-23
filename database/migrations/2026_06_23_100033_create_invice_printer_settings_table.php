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
        Schema::create('invice_printer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('formName');
            $table->string('printerName');
            $table->string('permssionName')->unique();
            $table->string('numOfCopies')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invice_printer_settings');
    }
};
