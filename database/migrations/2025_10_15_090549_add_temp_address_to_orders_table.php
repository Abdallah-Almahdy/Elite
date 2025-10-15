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
        Schema::table('orders', function (Blueprint $table)
        {
            $table->json('temp_address')->nullable()->after('address')->comment('to store temporary address for orders without saving it in customer_info table');
            $table->text('special_order_notes')->nullable()->after('temp_address')->comment('to store special notes for the order');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders_tabel', function (Blueprint $table) {
            //
        });
    }
};
