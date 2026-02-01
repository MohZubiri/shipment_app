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
        Schema::table('shipment', function (Blueprint $table) {
            $table->foreign('shippingno')
                ->references('id')
                ->on('shipping_line')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment', function (Blueprint $table) {
            $table->dropForeign(['shippingno']);
        });
    }
};
