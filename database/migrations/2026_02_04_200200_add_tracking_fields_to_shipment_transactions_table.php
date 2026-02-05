<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->foreignId('current_stage_id')
                ->nullable()
                ->constrained('shipment_stages')
                ->nullOnDelete();

            $table->string('origin_country')->nullable();
            $table->string('origin_port')->nullable();
            $table->string('factory_name')->nullable();
            $table->string('factory_address')->nullable();

            $table->date('manufacturing_date')->nullable();
            $table->date('factory_departure_date')->nullable();
            $table->date('port_departure_date')->nullable();
            $table->date('transit_arrival_date')->nullable();
            $table->date('customs_clearance_date')->nullable();
            $table->date('warehouse_arrival_date')->nullable();

            $table->string('carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('vessel_name')->nullable();
            $table->string('voyage_number')->nullable();

            $table->string('warehouse_location')->nullable();
            $table->string('warehouse_section')->nullable();
            $table->string('warehouse_zone')->nullable();

            $table->text('shipping_notes')->nullable();

            $table->index('current_stage_id');
            $table->index('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->dropForeign(['current_stage_id']);
            $table->dropIndex(['current_stage_id']);
            $table->dropColumn([
                'current_stage_id',
                'origin_country',
                'origin_port',
                'factory_name',
                'factory_address',
                'manufacturing_date',
                'factory_departure_date',
                'port_departure_date',
                'transit_arrival_date',
                'customs_clearance_date',
                'warehouse_arrival_date',
                'carrier',
                'tracking_number',
                'vessel_name',
                'voyage_number',
                'warehouse_location',
                'warehouse_section',
                'warehouse_zone',
                'shipping_notes',
            ]);
        });
    }
};
