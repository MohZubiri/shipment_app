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

        // Comprehensive list of permissions
        $permissions = [
            // Dashboard
            'view dashboard',

            // Shipments (Sea/Air)
            'view shipments',
            'create shipments',
            'edit shipments',
            'delete shipments',
            'export shipments',

            // Land Shipments
            'view land shipments',
            'create land shipments',
            'edit land shipments',
            'delete land shipments',

            // Local Customs Vehicles (Local Shipments)
            'view local shipments',
            'create local shipments',
            'edit local shipments',
            'delete local shipments',

            // Tracking
            'view tracking',
            'create tracking',
            'edit tracking',
            'delete tracking',

            // Customs Data
            'view customs data',
            'create customs data',
            'edit customs data',
            'delete customs data',

            // Settings / Master Data
            'view shipping lines', 'create shipping lines', 'edit shipping lines', 'delete shipping lines',
            'view customs ports', 'create customs ports', 'edit customs ports', 'delete customs ports',
            'view shipment stages', 'create shipment stages', 'edit shipment stages', 'delete shipment stages',
            'view warehouses', 'create warehouses', 'edit warehouses', 'delete warehouses',
            'view ship groups', 'create ship groups', 'edit ship groups', 'delete ship groups',
            'view departments', 'create departments', 'edit departments', 'delete departments',
            'view sections', 'create sections', 'edit sections', 'delete sections',
            'view shipment types', 'create shipment types', 'edit shipment types', 'delete shipment types',
            'view shipment statuses', 'create shipment statuses', 'edit shipment statuses', 'delete shipment statuses',
            'view documents', 'create documents', 'edit documents', 'delete documents',

            // Reports
            'view reports',
            'view shipments report',
            'view land shipping report',
            'view local customs report',
            'view summary report',

            // User Management
            'view users', 'create users', 'edit users', 'delete users',
            'view roles', 'create roles', 'edit roles', 'delete roles',
            'view permissions', 'create permissions', 'edit permissions', 'delete permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions($permissions); // Manager gets all permissions

        // Field Officer (Example restricted role)
        $fieldOfficer = Role::firstOrCreate(['name' => 'field_officer']);
        $fieldOfficer->syncPermissions([
            'view dashboard',
            'view shipments', 'create shipments', 'edit shipments',
            'view land shipments', 'create land shipments', 'edit land shipments',
            'view local shipments', 'create local shipments', 'edit local shipments',
            'view tracking', 'create tracking', 'edit tracking',
            'view reports',
        ]);

        // Accountant (Example restricted role)
        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $accountant->syncPermissions([
            'view dashboard',
            'view shipments',
            'view land shipments',
            'view local shipments',
            'export shipments',
            'view reports',
            'view shipments report',
            'view land shipping report',
            'view local customs report',
            'view summary report',
        ]);
    }
}
