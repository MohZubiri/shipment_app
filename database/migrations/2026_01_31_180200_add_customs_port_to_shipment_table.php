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
            $table->foreignId('customs_port_id')
                ->nullable()
                ->constrained('customs_port')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment', function (Blueprint $table) {
            $table->dropForeign(['customs_port_id']);
            $table->dropColumn('customs_port_id');
        });
    }
};
