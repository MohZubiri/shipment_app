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
        Schema::create('shipgroup', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('groupid');
            $table->timestamps();
            $table->softDeletes('delete_at');

            $table->index('groupid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipgroup');
    }
};
