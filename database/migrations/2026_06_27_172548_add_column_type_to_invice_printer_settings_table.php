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
        Schema::table('invice_printer_settings', function (Blueprint $table) {
            $table->enum('type', ['user', 'system'])->default('system');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invice_printer_settings', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('user_id');

        });
    }
};
