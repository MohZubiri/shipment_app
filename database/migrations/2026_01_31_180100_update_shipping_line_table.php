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
        Schema::table('shipping_line', function (Blueprint $table) {
            // Rename typo column if it exists
            if (Schema::hasColumn('shipping_line', 'campany_name')) {
                $table->renameColumn('campany_name', 'company_name');
            }
            
            // Add new columns for better shipping system experience
            if (!Schema::hasColumn('shipping_line', 'code')) {
                $table->string('code')->nullable()->after('name');
            }
            if (!Schema::hasColumn('shipping_line', 'contact_email')) {
                $table->string('contact_email')->nullable();
            }
            if (!Schema::hasColumn('shipping_line', 'phone')) {
                $table->string('phone')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_line', function (Blueprint $table) {
             if (Schema::hasColumn('shipping_line', 'company_name')) {
                $table->renameColumn('company_name', 'campany_name');
            }
            $table->dropColumn(['code', 'contact_email', 'phone']);
        });
    }
};
