<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipment_tracking', function (Blueprint $table) {
            $table->dateTime('event_date')->nullable()->useCurrent()->after('stage_id');
        });
    }

    public function down(): void
    {
        Schema::table('shipment_tracking', function (Blueprint $table) {
            $table->dropColumn('event_date');
        });
    }
};
