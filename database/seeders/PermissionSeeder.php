<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',

            'tasks.view',
            'tasks.create',
            'tasks.update',
            'tasks.delete',

            'create-public-tasks',
            'public-tasks.update',
            'public-tasks.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['code' => $perm],
                [
                    'name' => $perm,
                    'code' => $perm,
                ]
            );
        }
    }
}
