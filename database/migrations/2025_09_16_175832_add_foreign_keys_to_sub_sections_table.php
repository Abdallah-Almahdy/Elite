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
        Schema::table('sub_sections', function (Blueprint $table) {
            $table->foreign(['main_section_id'])->references(['id'])->on('sections')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_sections', function (Blueprint $table) {
            $table->dropForeign('sub_sections_main_section_id_foreign');
        });
    }
};
