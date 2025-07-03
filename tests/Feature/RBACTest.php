<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class RBACTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations and seeders
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_roles_can_be_created()
    {
        $role = Role::create([
            'name' => 'Test Role',
            'guard_name' => 'web',
            'description' => 'A test role'
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Test Role',
            'description' => 'A test role'
        ]);
    }

    public function test_permissions_can_be_created()
    {
        $permission = Permission::create([
            'name' => 'test-permission',
            'guard_name' => 'web',
            'description' => 'A test permission'
        ]);

        $this->assertDatabaseHas('permissions', [
            'name' => 'test-permission',
            'description' => 'A test permission'
        ]);
    }

    public function test_user_can_be_assigned_role()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'Test Role',
            'guard_name' => 'web'
        ]);

        $user->assignRole($role);

        $this->assertTrue($user->hasRole('Test Role'));
        $this->assertTrue($user->hasRole($role));
    }

    public function test_role_can_be_given_permission()
    {
        $role = Role::create([
            'name' => 'Test Role',
            'guard_name' => 'web'
        ]);
        $permission = Permission::create([
            'name' => 'test-permission',
            'guard_name' => 'web'
        ]);

        $role->givePermissionTo($permission);

        $this->assertTrue($role->hasPermissionTo('test-permission'));
        $this->assertTrue($role->hasPermissionTo($permission));
    }

    public function test_user_can_access_permission_through_role()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'Test Role',
            'guard_name' => 'web'
        ]);
        $permission = Permission::create([
            'name' => 'test-permission',
            'guard_name' => 'web'
        ]);

        $role->givePermissionTo($permission);
        $user->assignRole($role);

        $this->assertTrue($user->hasPermissionTo('test-permission'));
        $this->assertTrue($user->hasPermissionTo($permission));
    }

    public function test_user_can_be_given_direct_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'test-permission',
            'guard_name' => 'web'
        ]);

        $user->givePermissionTo($permission);

        $this->assertTrue($user->hasDirectPermission('test-permission'));
        $this->assertTrue($user->hasPermissionTo('test-permission'));
    }

    public function test_default_roles_are_seeded()
    {
        $this->assertDatabaseHas('roles', ['name' => 'Super Admin']);
        $this->assertDatabaseHas('roles', ['name' => 'Admin']);
        $this->assertDatabaseHas('roles', ['name' => 'Manager']);
        $this->assertDatabaseHas('roles', ['name' => 'Employee']);
        $this->assertDatabaseHas('roles', ['name' => 'Viewer']);
    }

    public function test_default_permissions_are_seeded()
    {
        $this->assertDatabaseHas('permissions', ['name' => 'view-dashboard']);
        $this->assertDatabaseHas('permissions', ['name' => 'view-users']);
        $this->assertDatabaseHas('permissions', ['name' => 'create-users']);
        $this->assertDatabaseHas('permissions', ['name' => 'edit-users']);
        $this->assertDatabaseHas('permissions', ['name' => 'delete-users']);
    }

    public function test_super_admin_has_all_permissions()
    {
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $totalPermissions = Permission::count();
        
        $this->assertEquals($totalPermissions, $superAdmin->permissions->count());
    }

    public function test_user_can_access_modules_based_on_permissions()
    {
        $user = User::factory()->create();
        $permission = Permission::create([
            'name' => 'view-products',
            'guard_name' => 'web'
        ]);

        $user->givePermissionTo($permission);

        $this->assertTrue($user->canAccess('products', 'view'));
        $this->assertFalse($user->canAccess('products', 'create'));
    }
}