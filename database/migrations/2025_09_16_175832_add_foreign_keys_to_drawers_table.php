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
        Schema::table('drawers', function (Blueprint $table) {
            $table->foreign(['cashier_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['safe_id'])->references(['id'])->on('safes')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drawers', function (Blueprint $table) {
            $table->dropForeign('drawers_cashier_id_foreign');
            $table->dropForeign('drawers_safe_id_foreign');
        });
    }
};
