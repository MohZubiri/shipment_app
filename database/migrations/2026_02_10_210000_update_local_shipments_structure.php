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
        Schema::table('local_customs_vehicles', function (Blueprint $table) {
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->date('factory_departure_date')->nullable();
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->date('warehouse_arrival_date')->nullable();
        });

        Schema::create('local_shipment_customs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_customs_vehicle_id')->constrained('local_customs_vehicles')->cascadeOnDelete();
            $table->foreignId('customs_port_id')->constrained('customs_port')->cascadeOnDelete();
            $table->date('entry_date')->nullable();
            $table->time('entry_time')->nullable();
            $table->date('exit_date')->nullable();
            $table->time('exit_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_shipment_customs');
        
        Schema::table('local_customs_vehicles', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn([
                'driver_name',
                'driver_phone',
                'factory_departure_date',
                'warehouse_id',
                'warehouse_arrival_date'
            ]);
        });
    }
};
