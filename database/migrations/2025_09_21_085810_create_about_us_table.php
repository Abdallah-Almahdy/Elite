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
        Schema::create('about_us', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('logo')->nullable();
            $table->string('company_name');
            $table->string('short_description');
            $table->longText('full_description');
            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->time('work_from');
            $table->time('work_to');
            $table->integer('experience_years')->nullable();
            $table->integer('happy_clients')->nullable();
            $table->integer('successful_projects')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};
