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
        Schema::create('local_customs_vehicles', function (Blueprint $table) {
            $table->id();
            $table->integer('serial_number');
            $table->string('vehicle_plate_number', 50)->nullable();
            $table->string('user_name', 200)->nullable();
            $table->time('arrival_time_from_branch')->nullable();
            $table->time('departure_time_to_branch')->nullable();
            $table->date('arrival_date_from_branch')->nullable();
            $table->string('destination', 200)->nullable();
            $table->string('cargo_type', 100)->nullable();
            $table->string('cargo_description', 500)->nullable();
            $table->string('vehicle_number', 50)->nullable();
            $table->date('manufacture_date')->nullable();
            $table->date('exit_date_from_manufacture')->nullable();
            $table->string('notes', 500)->nullable();
            $table->string('created_by', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('vehicle_plate_number', 'ix_vehicle_plate');
            $table->index('user_name', 'ix_user_name');
            $table->index('arrival_date_from_branch', 'ix_arrival_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_customs_vehicles');
    }
};
