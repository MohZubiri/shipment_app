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
        Schema::create('land_shipping', function (Blueprint $table) {
            $table->id();
            $table->string('operation_number', 50);
            $table->string('locomotive_number', 50)->nullable();
            $table->string('shipment_name', 200)->nullable();
            $table->string('declaration_number', 50)->nullable();
            $table->date('arrival_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->integer('docking_days')->nullable();
            $table->date('documents_sent_date')->nullable();
            $table->string('documents_type', 100)->nullable();
            $table->date('warehouse_arrival_date')->nullable();
            $table->timestamps();

            $table->index('operation_number');
            $table->index('declaration_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_reports');
    }
};
