<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'code' => 'super_admin'],
            ['name' => 'Admin', 'code' => 'admin'],
            ['name' => 'Manager', 'code' => 'manager'],
            ['name' => 'User', 'code' => 'user'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['code' => $role['code']],
                $role
            );
        }
    }
}
