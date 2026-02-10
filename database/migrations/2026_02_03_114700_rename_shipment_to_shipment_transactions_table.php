<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('shipment', 'shipment_transactions');

        // Update foreign key in shipment_documents table
        // Check if the table exists before trying to modify it
        if (Schema::hasTable('shipment_documents')) {
            Schema::table('shipment_documents', function (Blueprint $table) {
                // Check if the foreign key exists before dropping it
                $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'shipment_documents' AND COLUMN_NAME = 'shipment_id' AND REFERENCED_TABLE_NAME IS NOT NULL");

                if (!empty($foreignKeys)) {
                    $table->dropForeign(['shipment_id']);
                    $table->foreign('shipment_id')
                        ->references('id')
                        ->on('shipment_transactions')
                        ->cascadeOnUpdate()
                        ->cascadeOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update foreign key in shipment_documents table
        // Check if the table exists before trying to modify it
        if (Schema::hasTable('shipment_documents')) {
            Schema::table('shipment_documents', function (Blueprint $table) {
                // Check if the foreign key exists before dropping it
                $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'shipment_documents' AND COLUMN_NAME = 'shipment_id' AND REFERENCED_TABLE_NAME IS NOT NULL");

                if (!empty($foreignKeys)) {
                    $table->dropForeign(['shipment_id']);
                    $table->foreign('shipment_id')
                        ->references('id')
                        ->on('shipment')
                        ->cascadeOnUpdate()
                        ->cascadeOnDelete();
                }
            });
        }

        Schema::rename('shipment_transactions', 'shipment');
    }
};
