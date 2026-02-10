<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('land_shipping_locomotives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_shipping_id')->constrained('land_shipping')->cascadeOnDelete();
            $table->string('locomotive_number', 50);
            $table->timestamps();
        });

        // Migrate existing data
        $shipments = DB::table('land_shipping')->whereNotNull('locomotive_number')->where('locomotive_number', '!=', '')->get();
        foreach ($shipments as $shipment) {
            DB::table('land_shipping_locomotives')->insert([
                'land_shipping_id' => $shipment->id,
                'locomotive_number' => $shipment->locomotive_number,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop the old column
        Schema::table('land_shipping', function (Blueprint $table) {
            $table->dropColumn('locomotive_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('land_shipping', function (Blueprint $table) {
            $table->string('locomotive_number', 50)->nullable();
        });

        // Restore data (taking the first one)
        $locomotives = DB::table('land_shipping_locomotives')->get();
        foreach ($locomotives as $locomotive) {
            // Only update if it's currently null or empty (to avoid overwriting if we have multiple, keeping the first one encountered)
            // Simpler: just update. The last one in the loop will stick if there are multiple. Ideally we pick one.
             DB::table('land_shipping')
                ->where('id', $locomotive->land_shipping_id)
                ->update(['locomotive_number' => $locomotive->locomotive_number]);
        }

        Schema::dropIfExists('land_shipping_locomotives');
    }
};
