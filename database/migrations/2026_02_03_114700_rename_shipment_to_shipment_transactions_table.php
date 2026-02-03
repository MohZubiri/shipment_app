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
        Schema::rename('shipment', 'shipment_transactions');
        
        // Update foreign key in shipment_documents table
        Schema::table('shipment_documents', function (Blueprint $table) {
            $table->dropForeign(['shipment_id']);
            $table->foreign('shipment_id')
                ->references('id')
                ->on('shipment_transactions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_documents', function (Blueprint $table) {
            $table->dropForeign(['shipment_id']);
            $table->foreign('shipment_id')
                ->references('id')
                ->on('shipment')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
        
        Schema::rename('shipment_transactions', 'shipment');
    }
};
