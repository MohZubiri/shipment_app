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
        Schema::create('shipment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operationno');
            $table->unsignedBigInteger('shippmintno');
            $table->foreignId('shipgroupno')->nullable()
                ->constrained('shipgroup')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedBigInteger('datano')->nullable();
            $table->string('pillno');
            $table->string('pakingno')->nullable();
            $table->string('pilno');
            $table->string('orginalno')->nullable();
            $table->string('pillno2')->nullable();
            $table->string('pakingno2')->nullable();
            $table->string('pilno2')->nullable();
            $table->string('orginalno2')->nullable();
            $table->unsignedInteger('paperno')->nullable();
            $table->text('others')->nullable();
            $table->unsignedInteger('shipmtype');
            $table->foreignId('departmentno')
                ->constrained('departement')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('sectionno')
                ->constrained('section')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->date('sendingdate')->nullable();
            $table->date('officedate')->nullable();
            $table->date('workerdate')->nullable();
            $table->string('workername')->nullable();
            $table->unsignedSmallInteger('state');
            $table->date('dategase')->nullable();
            $table->unsignedInteger('park20')->default(0);
            $table->unsignedInteger('park40')->default(0);
            $table->string('dectype')->nullable();
            $table->unsignedBigInteger('shippingno')->nullable();
            $table->unsignedInteger('contatty')->nullable();
            $table->decimal('value', 50, 0)->nullable();
            $table->string('relayname')->nullable();
            $table->date('relaydate')->nullable();
            $table->text('relaycases')->nullable();
            $table->unsignedBigInteger('alarm')->nullable();
            $table->date('endallowdate')->nullable();
            $table->date('returndate')->nullable();
            $table->integer('stillday')->default(0);
            $table->timestamps();
            $table->softDeletes('delete_at');

            $table->index('shippingno');
            $table->index('shippmintno');
            $table->index('datano');
            $table->index('alarm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment');
    }
};
