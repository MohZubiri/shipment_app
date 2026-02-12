<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('display_name_ar')->nullable()->after('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_system')->default(false)->after('avatar_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('display_name_ar');
        });
    }
};
