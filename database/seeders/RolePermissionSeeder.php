<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'view shipments',
            'manage shipments',
            'export shipments',
            'view customs',
            'manage customs',
            'manage users',
            'manage roles',
            'manage permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'manager',
            'field_officer',
            'accountant',
        ];

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions($permissions);

        $fieldOfficer = Role::firstOrCreate(['name' => 'field_officer']);
        $fieldOfficer->syncPermissions([
            'view dashboard',
            'view shipments',
            'manage shipments',
            'view customs',
        ]);

        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $accountant->syncPermissions([
            'view dashboard',
            'view shipments',
            'export shipments',
            'view customs',
            'manage customs',
        ]);
    }
}
