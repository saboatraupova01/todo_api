<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('code', 'super_admin')->first();
        $admin = Role::where('code', 'admin')->first();
        $manager = Role::where('code', 'manager')->first();
        $user = Role::where('code', 'user')->first();

        $all = Permission::pluck('id');

        $superAdmin->permissions()->sync(Permission::all()->pluck('id'));


        $admin->permissions()->sync(
            Permission::whereNotIn('code', [
                'permissions.delete'
            ])->pluck('id')
        );

        $manager->permissions()->sync(
            Permission::whereIn('code', [
                'users.view',
                'users.create',
                'users.update',
                'tasks.view',
                'tasks.create',
                'tasks.update',
                'tasks.delete',
                'create-public-tasks',
                'public-tasks.update',
                'public-tasks.delete',
            ])->pluck('id')
        );

        $user->permissions()->sync(
            Permission::whereIn('code', [
                'tasks.view',
                'tasks.create',
                'tasks.update',
                'tasks.delete',
                'users.view',
            ])->pluck('id')
        );
    }
}
