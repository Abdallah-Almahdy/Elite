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
        Schema::create('section_printer_user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_sections_id')->constrained('sub_sections')->onDelete('cascade');
            $table->string('section_name');
            $table->string('printer_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_printer_user_settings');
    }
};
