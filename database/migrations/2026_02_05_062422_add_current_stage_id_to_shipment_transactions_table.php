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
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->foreignId('current_stage_id')->nullable()->after('state')->constrained('shipment_stages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->dropForeign(['current_stage_id']);
            $table->dropColumn('current_stage_id');
        });
    }
};
