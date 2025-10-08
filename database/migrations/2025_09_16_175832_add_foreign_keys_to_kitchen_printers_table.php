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
        Schema::table('kitchen_printers', function (Blueprint $table) {
            $table->foreign(['kitchen_id'])->references(['id'])->on('kitchen')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['printer_id'])->references(['id'])->on('printers')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kitchen_printers', function (Blueprint $table) {
            $table->dropForeign('kitchen_printers_kitchen_id_foreign');
            $table->dropForeign('kitchen_printers_printer_id_foreign');
        });
    }
};
