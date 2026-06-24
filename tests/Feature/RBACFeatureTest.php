<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RBACFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function seedRBAC()
    {
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::create([
                'name' => $perm,
                'code' => $perm,
            ]);
        }

        $role = Role::create([
            'name' => 'Admin',
            'code' => 'admin',
        ]);

        $role->permissions()->sync(Permission::pluck('id'));

        return $role;
    }

    /** @test */
    public function user_with_permission_can_access_route()
    {
        $role = $this->seedRBAC();

        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        // PASSPORT AUTH
        Passport::actingAs($user);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_without_permission_gets_forbidden()
    {
        $role = $this->seedRBAC();

        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        // удаляем permissions у роли
        $role->permissions()->detach();

        Passport::actingAs($user);

        $response = $this->getJson('/api/users');

        $response->assertStatus(403);
    }

    /** @test */
    public function role_can_be_assigned_to_user()
    {
        $role = $this->seedRBAC();

        $user = User::factory()->create();
        $user->roles()->sync([$role->id]);

        $this->assertTrue(
            $user->roles()->where('code', 'admin')->exists()
        );
    }

    /** @test */
    public function permissions_are_attached_to_role()
    {
        $this->seedRBAC();

        $role = Role::where('code', 'admin')->first();

        $this->assertNotEmpty($role->permissions);
    }
}
