<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_transaction_id')
                ->constrained('shipment_transactions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('stage_id')
                ->constrained('shipment_stages')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
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

            $table->index(['shipment_transaction_id', 'created_at']);
            $table->index('stage_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_tracking');
    }
};
