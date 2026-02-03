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
        Schema::create('shipment_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_transaction_id')->constrained('shipment_transactions')->onDelete('cascade');
            $table->string('invoice_number')->nullable();
            $table->string('packing_list_number')->nullable();
            $table->string('certificate_of_origin')->nullable();
            $table->string('bill_of_lading')->nullable();
            $table->integer('container_count')->default(1);
            $table->string('container_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_containers');
    }
};
