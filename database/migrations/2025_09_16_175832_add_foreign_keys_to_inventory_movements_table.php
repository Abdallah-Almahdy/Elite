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
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreign(['cashier_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['safe_id'])->references(['id'])->on('safes')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropForeign('inventory_movements_cashier_id_foreign');
            $table->dropForeign('inventory_movements_safe_id_foreign');
        });
    }
};
