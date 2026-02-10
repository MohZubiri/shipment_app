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
        Schema::table('land_shipping', function (Blueprint $table) {
            // Add customs_port_id, make it nullable for existing records
            $table->foreignId('customs_port_id')->nullable()->constrained('customs_port')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('land_shipping', function (Blueprint $table) {
            $table->dropForeign(['customs_port_id']);
            $table->dropColumn('customs_port_id');
        });
    }
};
