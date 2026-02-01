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
        Schema::create('shipping_line', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('campany_name');
            $table->unsignedInteger('time');
            $table->timestamps();
            $table->softDeletes('delete_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_line');
    }
};
