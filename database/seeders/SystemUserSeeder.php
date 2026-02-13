<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'super.system@shipments.local'],
            [
                'name' => 'Super System',
                'password' => Hash::make('SuperSystem!123'),
            ]
        );

      
      
      
        // تأكيد أنه مستخدم نظام مخفي
        if (!$user->is_system) {
            $user->forceFill(['is_system' => true])->save();
        }

        // منح جميع الصلاحيات عبر دور super-admin
        if (!$user->hasRole('super-admin')) {
            $user->assignRole('super-admin');
        }
    }
}
