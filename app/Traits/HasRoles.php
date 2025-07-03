<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRoles
{
    /**
     * Get all roles for the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
                    ->where('model_type', static::class);
    }

    /**
     * Get all permissions for the user through roles.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'model_has_permissions', 'model_id', 'permission_id')
                    ->where('model_type', static::class);
    }

    /**
     * Assign role to user.
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role && !$this->hasRole($role)) {
            $this->roles()->attach($role);
        }

        return $this;
    }

    /**
     * Remove role from user.
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role);
        }

        return $this;
    }

    /**
     * Check if user has role.
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return $this->roles->contains($role);
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Give permission to user directly.
     */
    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission && !$this->hasDirectPermission($permission)) {
            $this->permissions()->attach($permission);
        }

        return $this;
    }

    /**
     * Revoke permission from user.
     */
    public function revokePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission);
        }

        return $this;
    }

    /**
     * Check if user has permission directly.
     */
    public function hasDirectPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }

        return $this->permissions->contains($permission);
    }

    /**
     * Check if user has permission (through roles or directly).
     */
    public function hasPermissionTo($permission)
    {
        // Check direct permission
        if ($this->hasDirectPermission($permission)) {
            return true;
        }

        // Check permission through roles
        foreach ($this->roles as $role) {
            if ($role->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user can access a specific module.
     */
    public function canAccess($module, $action = 'view')
    {
        $permission = $action . '-' . $module;
        return $this->hasPermissionTo($permission);
    }

    /**
     * Get user's primary role (first role).
     */
    public function getPrimaryRole()
    {
        return $this->roles->first();
    }

    /**
     * Get user's role names.
     */
    public function getRoleNames()
    {
        return $this->roles->pluck('name')->toArray();
    }
}