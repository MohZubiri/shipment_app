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
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('departement')
                ->nullOnDelete();
            $table->foreignId('section_id')
                ->nullable()
                ->constrained('section')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('land_shipping', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['section_id']);
            $table->dropColumn(['company_id', 'section_id']);
        });
    }
};
