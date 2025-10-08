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
        Schema::create('sub_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->longText('description')->nullable();
            $table->longText('photo');
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('main_section_id')->index('sub_sections_main_section_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_sections');
    }
};
