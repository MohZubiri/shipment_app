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

        // Comprehensive list of permissions with Arabic labels
        $permissions = [
            // Dashboard
            'view dashboard' => 'عرض لوحة التحكم',

            // Shipments (Sea/Air)
            'view shipments' => 'عرض الشحنات البحرية/الجوية',
            'create shipments' => 'إنشاء شحنة',
            'edit shipments' => 'تعديل شحنة',
            'delete shipments' => 'حذف شحنة',
            'export shipments' => 'تصدير الشحنات',

            // Land Shipments
            'view land shipments' => 'عرض الشحنات البرية',
            'create land shipments' => 'إنشاء شحنة برية',
            'edit land shipments' => 'تعديل شحنة برية',
            'delete land shipments' => 'حذف شحنة برية',
            'export land shipments' => 'تصدير الشحنات البرية',

            // Local Customs Vehicles (Local Shipments)
            'view local shipments' => 'عرض مركبات الجمارك المحلية',
            'create local shipments' => 'إضافة مركبة جمارك محلية',
            'edit local shipments' => 'تعديل مركبة جمارك محلية',
            'delete local shipments' => 'حذف مركبة جمارك محلية',

            // Tracking
            'view tracking' => 'عرض التتبع',
            'create tracking' => 'إضافة تتبع',
            'edit tracking' => 'تعديل تتبع',
            'delete tracking' => 'حذف تتبع',

            // Customs Data
            'view customs data' => 'عرض بيانات الجمارك',
            'create customs data' => 'إضافة بيانات جمارك',
            'edit customs data' => 'تعديل بيانات جمارك',
            'delete customs data' => 'حذف بيانات جمارك',

            // Settings / Master Data
            'view shipping lines' => 'عرض الخطوط الملاحية', 'create shipping lines' => 'إنشاء خط ملاحي', 'edit shipping lines' => 'تعديل خط ملاحي', 'delete shipping lines' => 'حذف خط ملاحي',
            'view customs ports' => 'عرض المنافذ الجمركية', 'create customs ports' => 'إنشاء منفذ جمركي', 'edit customs ports' => 'تعديل منفذ جمركي', 'delete customs ports' => 'حذف منفذ جمركي',
            'view shipment stages' => 'عرض مراحل الشحن', 'create shipment stages' => 'إنشاء مرحلة شحن', 'edit shipment stages' => 'تعديل مرحلة شحن', 'delete shipment stages' => 'حذف مرحلة شحن',
            'view warehouses' => 'عرض المخازن', 'create warehouses' => 'إنشاء مخزن', 'edit warehouses' => 'تعديل مخزن', 'delete warehouses' => 'حذف مخزن',
            'view ship groups' => 'عرض مجموعات الشحن', 'create ship groups' => 'إنشاء مجموعة شحن', 'edit ship groups' => 'تعديل مجموعة شحن', 'delete ship groups' => 'حذف مجموعة شحن',
            'view departments' => 'عرض الشركات', 'create departments' => 'إنشاء شركة', 'edit departments' => 'تعديل شركة', 'delete departments' => 'حذف شركة',
            'view sections' => 'عرض الأقسام', 'create sections' => 'إنشاء قسم', 'edit sections' => 'تعديل قسم', 'delete sections' => 'حذف قسم',
            'view shipment types' => 'عرض أنواع الشحن', 'create shipment types' => 'إنشاء نوع شحن', 'edit shipment types' => 'تعديل نوع شحن', 'delete shipment types' => 'حذف نوع شحن',
            'view shipment statuses' => 'عرض حالات الشحن', 'create shipment statuses' => 'إنشاء حالة شحن', 'edit shipment statuses' => 'تعديل حالة شحن', 'delete shipment statuses' => 'حذف حالة شحن',
            'view documents' => 'عرض المستندات', 'create documents' => 'إنشاء مستند', 'edit documents' => 'تعديل مستند', 'delete documents' => 'حذف مستند',

            // Reports
            'view reports' => 'عرض التقارير',
            'view shipments report' => 'تقرير الشحنات البحرية/الجوية',
            'view land shipping report' => 'تقرير الشحنات البرية',
            'view local customs report' => 'تقرير مركبات الجمارك المحلية',
            'view summary report' => 'تقرير الملخص العام',

            // User Management
            'view users' => 'عرض المستخدمين', 'create users' => 'إنشاء مستخدم', 'edit users' => 'تعديل مستخدم', 'delete users' => 'حذف مستخدم',
            'view roles' => 'عرض الأدوار', 'create roles' => 'إنشاء دور', 'edit roles' => 'تعديل دور', 'delete roles' => 'حذف دور',
            'view permissions' => 'عرض الصلاحيات', 'create permissions' => 'إنشاء صلاحية', 'edit permissions' => 'تعديل صلاحية', 'delete permissions' => 'حذف صلاحية',
        ];

        foreach ($permissions as $permission => $labelAr) {
            $perm = Permission::firstOrCreate(['name' => $permission]);
            $perm->update(['display_name_ar' => $labelAr]);
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(array_keys($permissions)); // Super Admin gets all permissions

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions(array_keys($permissions)); // Manager also gets all permissions for now

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
            'export land shipments',
            'view reports',
            'view shipments report',
            'view land shipping report',
            'view local customs report',
            'view summary report',
        ]);
    }
}
