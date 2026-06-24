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

        // SUPER ADMIN → всё
        $superAdmin->permissions()->sync(Permission::all()->pluck('id'));
        // ADMIN → почти всё кроме permissions delete
        $admin->permissions()->sync(
            Permission::whereNotIn('code', [
                'permissions.delete'
            ])->pluck('id')
        );

        // MANAGER → только работа с users
        $manager->permissions()->sync(
            Permission::whereIn('code', [
                'users.view',
                'users.create',
                'users.update',
            ])->pluck('id')
        );

        // USER → только просмотр
        $user->permissions()->sync(
            Permission::where('code', 'users.view')->pluck('id')
        );
    }
}
