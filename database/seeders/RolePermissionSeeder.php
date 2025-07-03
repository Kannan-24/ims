<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for different modules
        $modules = [
            'users',
            'customers', 
            'suppliers',
            'products',
            'services',
            'purchases',
            'stocks',
            'quotations',
            'invoices',
            'payments',
            'reports',
            'roles',
            'permissions'
        ];

        $actions = ['view', 'create', 'edit', 'delete'];
        
        // Create permissions
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}-{$module}",
                    'guard_name' => 'web',
                    'description' => "Can {$action} {$module}"
                ]);
            }
        }

        // Create additional specific permissions
        $specificPermissions = [
            'view-dashboard' => 'Can view dashboard',
            'manage-system' => 'Can manage system settings',
            'view-analytics' => 'Can view analytics and reports',
            'export-data' => 'Can export data',
            'import-data' => 'Can import data',
        ];

        foreach ($specificPermissions as $name => $description) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
                'description' => $description
            ]);
        }

        // Create roles
        $roles = [
            'Super Admin' => 'Full system access',
            'Admin' => 'Administrative access',
            'Manager' => 'Management access',
            'Employee' => 'Basic employee access',
            'Viewer' => 'Read-only access'
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
                'description' => $description
            ]);
        }

        // Assign permissions to roles
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $admin = Role::where('name', 'Admin')->first();
        $manager = Role::where('name', 'Manager')->first();
        $employee = Role::where('name', 'Employee')->first();
        $viewer = Role::where('name', 'Viewer')->first();

        // Super Admin gets all permissions
        $superAdmin->permissions()->sync(Permission::all());

        // Admin gets most permissions except system management
        $adminPermissions = Permission::whereNotIn('name', ['manage-system'])->get();
        $admin->permissions()->sync($adminPermissions);

        // Manager gets management permissions
        $managerPermissions = Permission::where('name', 'like', 'view-%')
            ->orWhere('name', 'like', 'create-%')
            ->orWhere('name', 'like', 'edit-%')
            ->whereNotIn('name', [
                'create-users', 'edit-users', 'delete-users',
                'create-roles', 'edit-roles', 'delete-roles',
                'create-permissions', 'edit-permissions', 'delete-permissions'
            ])->get();
        $manager->permissions()->sync($managerPermissions);

        // Employee gets basic permissions
        $employeePermissions = Permission::where('name', 'like', 'view-%')
            ->orWhereIn('name', [
                'create-customers', 'edit-customers',
                'create-suppliers', 'edit-suppliers',
                'create-products', 'edit-products',
                'create-quotations', 'edit-quotations',
                'view-dashboard'
            ])->get();
        $employee->permissions()->sync($employeePermissions);

        // Viewer gets only view permissions
        $viewerPermissions = Permission::where('name', 'like', 'view-%')->get();
        $viewer->permissions()->sync($viewerPermissions);
    }
}