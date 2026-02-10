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
        // Step 1: Clean up pre-existing company_id column on shipment_transactions
        // (added by an earlier migration, nullable, references old companies table)
        if (Schema::hasColumn('shipment_transactions', 'company_id')) {
            Schema::table('shipment_transactions', function (Blueprint $table) {
                $fks = collect(DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'shipment_transactions' AND COLUMN_NAME = 'company_id' AND REFERENCED_TABLE_NAME IS NOT NULL"));
                foreach ($fks as $fk) {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                }
                $table->dropColumn('company_id');
            });
        }

        // Step 2: Drop the pre-existing companies table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('companies');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Step 3: Drop foreign keys that reference old departement/section tables
        // Use explicit names from the database (may have already been dropped by a previous failed run)
        $this->dropForeignIfExists('shipment_transactions', 'shipment_departmentno_foreign');
        $this->dropForeignIfExists('shipment_transactions', 'shipment_sectionno_foreign');
        $this->dropForeignIfExists('land_shipping', 'land_shipping_company_id_foreign');
        $this->dropForeignIfExists('land_shipping', 'land_shipping_section_id_foreign');
        $this->dropForeignIfExists('local_customs_vehicles', 'local_customs_vehicles_company_id_foreign');
        $this->dropForeignIfExists('local_customs_vehicles', 'local_customs_vehicles_section_id_foreign');

        // Step 4: Rename tables
        Schema::rename('departement', 'companies');
        Schema::rename('section', 'departements');

        // Step 5: Rename columns in shipment_transactions
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->renameColumn('departmentno', 'company_id');
            $table->renameColumn('sectionno', 'department_id');
        });

        // Rename columns in land_shipping
        Schema::table('land_shipping', function (Blueprint $table) {
            $table->renameColumn('section_id', 'department_id');
        });

        // Rename columns in local_customs_vehicles
        Schema::table('local_customs_vehicles', function (Blueprint $table) {
            $table->renameColumn('section_id', 'department_id');
        });

        // Step 6: Re-add foreign keys with new table names
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('department_id')->references('id')->on('departements')
                ->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::table('land_shipping', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')
                ->nullOnDelete();
            $table->foreign('department_id')->references('id')->on('departements')
                ->nullOnDelete();
        });

        Schema::table('local_customs_vehicles', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')
                ->nullOnDelete();
            $table->foreign('department_id')->references('id')->on('departements')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new foreign keys
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['department_id']);
        });

        Schema::table('land_shipping', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['department_id']);
        });

        Schema::table('local_customs_vehicles', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['department_id']);
        });

        // Rename columns back
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->renameColumn('company_id', 'departmentno');
            $table->renameColumn('department_id', 'sectionno');
        });

        Schema::table('land_shipping', function (Blueprint $table) {
            $table->renameColumn('department_id', 'section_id');
        });

        Schema::table('local_customs_vehicles', function (Blueprint $table) {
            $table->renameColumn('department_id', 'section_id');
        });

        // Rename tables back
        Schema::rename('companies', 'departement');
        Schema::rename('departements', 'section');

        // Re-add old foreign keys
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->foreign('departmentno')->references('id')->on('departement')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('sectionno')->references('id')->on('section')
                ->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::table('land_shipping', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('departement')
                ->nullOnDelete();
            $table->foreign('section_id')->references('id')->on('section')
                ->nullOnDelete();
        });

        Schema::table('local_customs_vehicles', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('departement')
                ->nullOnDelete();
            $table->foreign('section_id')->references('id')->on('section')
                ->nullOnDelete();
        });

        // Re-create the old companies table
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Re-add the company_id column to shipment_transactions
        Schema::table('shipment_transactions', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('departmentno')
                ->constrained('companies')->nullOnDelete();
        });
    }

    /**
     * Helper to drop a foreign key only if it exists.
     */
    private function dropForeignIfExists(string $table, string $foreignKey): void
    {
        $exists = DB::select(
            "SELECT COUNT(*) as cnt FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$table, $foreignKey]
        );

        if ($exists[0]->cnt > 0) {
            Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey);
            });
        }
    }
};
