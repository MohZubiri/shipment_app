<?php

use App\Http\Controllers\Admin\ShippingLineController;
use App\Http\Controllers\Admin\CustomsPortController;
use App\Http\Controllers\Admin\ShipGroupController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\PermissionManagementController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ShipmentStageController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\CustomsDataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandShippingController;
use App\Http\Controllers\LocalCustomsVehicleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShipmentTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'permission:view dashboard'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// المسارات الثابتة يجب أن تأتي قبل المسارات الديناميكية
Route::get('/shipments/create', [ShipmentController::class, 'create'])
    ->middleware(['auth', 'permission:manage shipments'])
    ->name('shipments.create');

Route::post('/shipments', [ShipmentController::class, 'store'])
    ->middleware(['auth', 'permission:manage shipments'])
    ->name('shipments.store');

Route::get('/shipments/export', [ShipmentController::class, 'export'])
    ->middleware(['auth', 'permission:export shipments'])
    ->name('shipments.export');

Route::middleware(['auth', 'permission:view shipments'])->group(function () {
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::get('/shipments/documents/{document}/download', [ShipmentController::class, 'downloadDocument'])
        ->name('shipments.documents.download');
    Route::get('/road-shipments', [LandShippingController::class, 'index'])->name('road-shipments.index');
    Route::get('/local-shipments', [LocalCustomsVehicleController::class, 'index'])->name('local-shipments.index');
    Route::get('/shipments/{shipment}/tracking', [ShipmentTrackingController::class, 'index'])->name('shipments.tracking.index');
    Route::get('/shipments/{shipment}/tracking/create', [ShipmentTrackingController::class, 'create'])->name('shipments.tracking.create');
    Route::post('/shipments/{shipment}/tracking', [ShipmentTrackingController::class, 'store'])->name('shipments.tracking.store');
    Route::get('/shipments/{shipment}/tracking/{tracking}/edit', [ShipmentTrackingController::class, 'edit'])->name('shipments.tracking.edit');
    Route::put('/shipments/{shipment}/tracking/{tracking}', [ShipmentTrackingController::class, 'update'])->name('shipments.tracking.update');
    Route::delete('/shipments/{shipment}/tracking/{tracking}', [ShipmentTrackingController::class, 'destroy'])->name('shipments.tracking.destroy');
    Route::get('/shipments/{shipment}/tracking/container-info', [ShipmentTrackingController::class, 'getContainerInfo'])->name('shipments.tracking.container-info');
});

Route::middleware(['auth', 'permission:manage shipments'])->group(function () {
    Route::get('/shipments/{shipment}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
    Route::put('/shipments/{shipment}', [ShipmentController::class, 'update'])->name('shipments.update');
    Route::delete('/shipments/{shipment}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');

    Route::get('/road-shipments/create', [LandShippingController::class, 'create'])->name('road-shipments.create');
    Route::post('/road-shipments', [LandShippingController::class, 'store'])->name('road-shipments.store');
    Route::get('/road-shipments/{landShipping}/edit', [LandShippingController::class, 'edit'])->name('road-shipments.edit');
    Route::put('/road-shipments/{landShipping}', [LandShippingController::class, 'update'])->name('road-shipments.update');
    Route::delete('/road-shipments/{landShipping}', [LandShippingController::class, 'destroy'])->name('road-shipments.destroy');

    Route::get('/local-shipments/create', [LocalCustomsVehicleController::class, 'create'])->name('local-shipments.create');
    Route::post('/local-shipments', [LocalCustomsVehicleController::class, 'store'])->name('local-shipments.store');
    Route::get('/local-shipments/{localCustomsVehicle}/edit', [LocalCustomsVehicleController::class, 'edit'])->name('local-shipments.edit');
    Route::put('/local-shipments/{localCustomsVehicle}', [LocalCustomsVehicleController::class, 'update'])->name('local-shipments.update');
    Route::delete('/local-shipments/{localCustomsVehicle}', [LocalCustomsVehicleController::class, 'destroy'])->name('local-shipments.destroy');

    // Shipping Lines
    Route::resource('/admin/shipping-lines', ShippingLineController::class)->names([
        'index' => 'admin.shipping-lines.index',
        'create' => 'admin.shipping-lines.create',
        'store' => 'admin.shipping-lines.store',
        'edit' => 'admin.shipping-lines.edit',
        'update' => 'admin.shipping-lines.update',
        'destroy' => 'admin.shipping-lines.destroy',
    ]);

    // Shipment Stages
    Route::resource('/admin/shipment-stages', ShipmentStageController::class)->names([
        'index' => 'admin.shipment-stages.index',
        'create' => 'admin.shipment-stages.create',
        'store' => 'admin.shipment-stages.store',
        'edit' => 'admin.shipment-stages.edit',
        'update' => 'admin.shipment-stages.update',
        'destroy' => 'admin.shipment-stages.destroy',
    ])->except(['show']);

    // Warehouses
    Route::resource('/admin/warehouses', WarehouseController::class)->names([
        'index' => 'admin.warehouses.index',
        'create' => 'admin.warehouses.create',
        'store' => 'admin.warehouses.store',
        'show' => 'admin.warehouses.show',
        'edit' => 'admin.warehouses.edit',
        'update' => 'admin.warehouses.update',
        'destroy' => 'admin.warehouses.destroy',
    ]);

    Route::get('/admin/warehouses/by-stage/{stageId}', [WarehouseController::class, 'getWarehousesByStage'])
        ->name('admin.warehouses.by-stage');

    // Ship Groups
    Route::resource('/admin/ship-groups', ShipGroupController::class)->names([
        'index' => 'admin.ship-groups.index',
        'create' => 'admin.ship-groups.create',
        'store' => 'admin.ship-groups.store',
        'edit' => 'admin.ship-groups.edit',
        'update' => 'admin.ship-groups.update',
        'destroy' => 'admin.ship-groups.destroy',
    ]);

    // Departments
    Route::resource('/admin/departments', DepartmentController::class)->names([
        'index' => 'admin.departments.index',
        'create' => 'admin.departments.create',
        'store' => 'admin.departments.store',
        'edit' => 'admin.departments.edit',
        'update' => 'admin.departments.update',
        'destroy' => 'admin.departments.destroy',
    ]);

    // Sections
    Route::resource('/admin/sections', SectionController::class)->names([
        'index' => 'admin.sections.index',
        'create' => 'admin.sections.create',
        'store' => 'admin.sections.store',
        'edit' => 'admin.sections.edit',
        'update' => 'admin.sections.update',
        'destroy' => 'admin.sections.destroy',
    ]);

    // Shipment Types
    Route::resource('/admin/shipment-types', \App\Http\Controllers\Admin\ShipmentTypeController::class)->names([
        'index' => 'admin.shipment-types.index',
        'create' => 'admin.shipment-types.create',
        'store' => 'admin.shipment-types.store',
        'edit' => 'admin.shipment-types.edit',
        'update' => 'admin.shipment-types.update',
        'destroy' => 'admin.shipment-types.destroy',
    ]);

    // Shipment Statuses
    Route::resource('/admin/shipment-statuses', \App\Http\Controllers\Admin\ShipmentStatusController::class)->names([
        'index' => 'admin.shipment-statuses.index',
        'create' => 'admin.shipment-statuses.create',
        'store' => 'admin.shipment-statuses.store',
        'edit' => 'admin.shipment-statuses.edit',
        'update' => 'admin.shipment-statuses.update',
        'destroy' => 'admin.shipment-statuses.destroy',
    ]);

    // Shipments (Master Data)
    Route::resource('/admin/shipments', \App\Http\Controllers\Admin\ShipmentController::class)->names([
        'index' => 'admin.shipments.index',
        'create' => 'admin.shipments.create',
        'show' => 'admin.shipments.show',
        'store' => 'admin.shipments.store',
        'edit' => 'admin.shipments.edit',
        'update' => 'admin.shipments.update',
        'destroy' => 'admin.shipments.destroy',
    ]);

    // Documents
    Route::resource('/admin/documents', \App\Http\Controllers\Admin\DocumentController::class)->names([
        'index' => 'admin.documents.index',
        'create' => 'admin.documents.create',
        'store' => 'admin.documents.store',
        'edit' => 'admin.documents.edit',
        'update' => 'admin.documents.update',
        'destroy' => 'admin.documents.destroy',
    ]);
});

Route::middleware(['auth', 'permission:view shipments'])->group(function () {
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
});

Route::middleware(['auth', 'permission:view customs'])->group(function () {
    Route::get('/customs-data', [CustomsDataController::class, 'index'])->name('customs.index');
});

Route::middleware(['auth', 'permission:manage customs'])->group(function () {
    Route::get('/customs-data/create', [CustomsDataController::class, 'create'])->name('customs.create');
    Route::post('/customs-data', [CustomsDataController::class, 'store'])->name('customs.store');
    Route::get('/customs-data/{customsData}/edit', [CustomsDataController::class, 'edit'])->name('customs.edit');
    Route::put('/customs-data/{customsData}', [CustomsDataController::class, 'update'])->name('customs.update');
    Route::delete('/customs-data/{customsData}', [CustomsDataController::class, 'destroy'])->name('customs.destroy');

    // Customs Ports
    Route::resource('/admin/customs-ports', CustomsPortController::class)->names([
        'index' => 'admin.customs-ports.index',
        'create' => 'admin.customs-ports.create',
        'store' => 'admin.customs-ports.store',
        'edit' => 'admin.customs-ports.edit',
        'update' => 'admin.customs-ports.update',
        'destroy' => 'admin.customs-ports.destroy',
    ]);
});

Route::middleware(['auth', 'permission:manage users'])->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});

Route::middleware(['auth', 'permission:manage roles'])->group(function () {
    Route::get('/admin/roles', [RoleManagementController::class, 'index'])->name('admin.roles.index');
    Route::get('/admin/roles/create', [RoleManagementController::class, 'create'])->name('admin.roles.create');
    Route::post('/admin/roles', [RoleManagementController::class, 'store'])->name('admin.roles.store');
    Route::get('/admin/roles/{role}/edit', [RoleManagementController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/admin/roles/{role}', [RoleManagementController::class, 'update'])->name('admin.roles.update');
    Route::delete('/admin/roles/{role}', [RoleManagementController::class, 'destroy'])->name('admin.roles.destroy');
});

Route::middleware(['auth', 'permission:manage permissions'])->group(function () {
    Route::get('/admin/permissions', [PermissionManagementController::class, 'index'])->name('admin.permissions.index');
    Route::get('/admin/permissions/create', [PermissionManagementController::class, 'create'])->name('admin.permissions.create');
    Route::post('/admin/permissions', [PermissionManagementController::class, 'store'])->name('admin.permissions.store');
    Route::get('/admin/permissions/{permission}/edit', [PermissionManagementController::class, 'edit'])->name('admin.permissions.edit');
    Route::put('/admin/permissions/{permission}', [PermissionManagementController::class, 'update'])->name('admin.permissions.update');
    Route::delete('/admin/permissions/{permission}', [PermissionManagementController::class, 'destroy'])->name('admin.permissions.destroy');
});

Route::middleware(['auth', 'permission:view reports'])->prefix('admin/reports')->name('admin.reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/shipments', [ReportController::class, 'shipmentReport'])->name('shipments');
    Route::get('/shipments/pdf', [ReportController::class, 'shipmentReportPdf'])->name('shipments.pdf');
});

require __DIR__.'/auth.php';
