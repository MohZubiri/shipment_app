<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'admin@shipments.local')->first();

if (!$user) {
    echo "User not found!\n";
    exit(1);
}

echo "User: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "\nRoles:\n";
foreach ($user->roles as $role) {
    echo "  - {$role->name}\n";
}
echo "\nPermissions:\n";
foreach ($user->getAllPermissions() as $permission) {
    echo "  - {$permission->name}\n";
}
echo "\nHas 'manage shipments': " . ($user->hasPermissionTo('manage shipments') ? 'YES' : 'NO') . "\n";
