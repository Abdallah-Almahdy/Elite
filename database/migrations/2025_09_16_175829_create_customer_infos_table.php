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
        Schema::create('customer_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('email');
            $table->text('firstName');
            $table->text('lastName');
            $table->text('profileImage')->nullable();
            $table->text('phonenum')->nullable();
            $table->text('addressCountry')->nullable();
            $table->text('addresscity')->nullable();
            $table->text('addressstreet')->nullable();
            $table->text('addressbuildingNumber')->nullable();
            $table->text('addressfloorNumber')->nullable();
            $table->text('gender')->nullable();
            $table->text('birthDate')->nullable();
            $table->text('disSign')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->index('customer_infos_user_id_foreign');
            $table->string('addressApartmentNumber')->nullable();
            $table->text('addressCountry2')->nullable();
            $table->text('addresscity2')->nullable();
            $table->text('addressstreet2')->nullable();
            $table->text('addressbuildingNumber2')->nullable();
            $table->text('addressfloorNumber2')->nullable();
            $table->text('addressApartmentNumber2')->nullable();
            $table->text('disSign2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_infos');
    }
};
