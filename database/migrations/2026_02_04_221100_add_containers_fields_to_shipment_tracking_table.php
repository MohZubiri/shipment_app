<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipment_tracking', function (Blueprint $table) {
            $table->unsignedInteger('container_count')->nullable()->after('event_date');
            $table->text('container_numbers')->nullable()->after('container_count');
        });
    }

    public function down(): void
    {
        Schema::table('shipment_tracking', function (Blueprint $table) {
            $table->dropColumn(['container_count', 'container_numbers']);
        });
    }
};
