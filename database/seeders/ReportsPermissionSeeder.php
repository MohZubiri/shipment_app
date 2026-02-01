<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ReportsPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create the permission
        $permission = Permission::firstOrCreate(['name' => 'view reports']);

        // Assign to Admin role
        $role = Role::where('name', 'admin')->first();
        if ($role) {
            $role->givePermissionTo($permission);
        }
    }
}
