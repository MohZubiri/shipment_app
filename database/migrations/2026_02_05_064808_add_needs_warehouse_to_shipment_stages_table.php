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
        Schema::table('shipment_stages', function (Blueprint $table) {
            $table->boolean('needs_warehouse')->default(false)->after('needs_containers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_stages', function (Blueprint $table) {
            $table->dropColumn('needs_warehouse');
        });
    }
};
