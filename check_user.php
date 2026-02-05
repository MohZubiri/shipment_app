<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== User Check ===\n";
echo "Users count: " . \App\Models\User::count() . "\n";

$user = \App\Models\User::first();
if ($user) {
    echo "User: {$user->name} - {$user->email}\n";
    echo "Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "Permissions: " . $user->getAllPermissions()->pluck('name')->implode(', ') . "\n";
    echo "Has manage shipments: " . ($user->can('manage shipments') ? 'YES' : 'NO') . "\n";
} else {
    echo "No users found\n";
}

echo "\n=== Permissions ===\n";
echo "Permissions count: " . \Spatie\Permission\Models\Permission::count() . "\n";
echo "Roles count: " . \Spatie\Permission\Models\Role::count() . "\n";
