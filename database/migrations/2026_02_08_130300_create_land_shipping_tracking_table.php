<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('land_shipping_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_shipping_id')
                ->constrained('land_shipping')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('stage_id')
                ->constrained('shipment_stages')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->dateTime('event_date')->nullable()->useCurrent();
            $table->unsignedInteger('container_count')->nullable();
            $table->text('container_numbers')->nullable();
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();

            $table->index(['land_shipping_id', 'created_at']);
            $table->index('stage_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('land_shipping_tracking');
    }
};
