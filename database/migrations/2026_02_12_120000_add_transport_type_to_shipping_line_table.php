<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_line', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_line', 'transport_type')) {
                $table->string('transport_type', 20)->default('sea')->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipping_line', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_line', 'transport_type')) {
                $table->dropColumn('transport_type');
            }
        });
    }
};
