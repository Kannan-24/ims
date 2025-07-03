# Role-Based Access Control (RBAC) System

This IMS project now includes a comprehensive Role-Based Access Control (RBAC) system that provides granular access control for different user types.

## Features

### Core Components
- **Roles**: Define user roles with specific permissions
- **Permissions**: Granular permissions for different actions and modules
- **Users**: Assign roles to users for access control
- **Middleware**: Route protection based on roles and permissions

### Default Roles

1. **Super Admin**
   - Full system access
   - Can manage all roles and permissions
   - Access to all modules and features

2. **Admin**
   - Administrative access (excluding system management)
   - Can manage most modules
   - Cannot manage system-level settings

3. **Manager**
   - Management-level access
   - Can view, create, and edit most content
   - Cannot manage users, roles, or permissions

4. **Employee**
   - Basic operational access
   - Can view most content and create/edit basic records
   - Limited to day-to-day operations

5. **Viewer**
   - Read-only access
   - Can only view content across modules
   - No create, edit, or delete permissions

### Permission Structure

Permissions follow the format: `{action}-{module}`

**Actions:**
- `view` - Can view/read content
- `create` - Can create new records
- `edit` - Can modify existing records
- `delete` - Can remove records

**Modules:**
- `users` - User management
- `customers` - Customer management
- `suppliers` - Supplier management
- `products` - Product management
- `services` - Service management
- `purchases` - Purchase management
- `stocks` - Stock management
- `quotations` - Quotation management
- `invoices` - Invoice management
- `payments` - Payment management
- `reports` - Reports and analytics
- `roles` - Role management
- `permissions` - Permission management

**Examples:**
- `view-customers` - Can view customer list and details
- `create-products` - Can create new products
- `edit-invoices` - Can modify existing invoices
- `delete-users` - Can delete user accounts

## Usage

### Checking Permissions in Controllers

```php
// Check if user has specific permission
if (auth()->user()->hasPermissionTo('view-customers')) {
    // User can view customers
}

// Check if user has role
if (auth()->user()->hasRole('Admin')) {
    // User is an admin
}

// Check if user can access module with specific action
if (auth()->user()->canAccess('products', 'create')) {
    // User can create products
}
```

### Using Middleware in Routes

```php
// Protect route with role
Route::middleware(['role:Admin,Super Admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Protect route with permission
Route::middleware(['permission:view-customers'])->group(function () {
    Route::get('customers', [CustomerController::class, 'index']);
});
```

### Blade Directives

```blade
{{-- Check role --}}
@role('Admin')
    <p>You are an admin!</p>
@endrole

{{-- Check permission --}}
@can('view-customers')
    <a href="{{ route('customers.index') }}">View Customers</a>
@endcan

{{-- Custom permission directive --}}
@permission('create-products')
    <button>Create Product</button>
@endpermission
```

### Managing Roles and Permissions

#### Assign Role to User
```php
$user = User::find(1);
$role = Role::where('name', 'Manager')->first();
$user->assignRole($role);
```

#### Give Permission to Role
```php
$role = Role::where('name', 'Employee')->first();
$permission = Permission::where('name', 'view-products')->first();
$role->givePermissionTo($permission);
```

#### Give Direct Permission to User
```php
$user = User::find(1);
$permission = Permission::where('name', 'export-data')->first();
$user->givePermissionTo($permission);
```

## Administrative Interface

### Role Management (`/ims/roles`)
- View all roles and their permissions
- Create new roles with custom permissions
- Edit existing roles and their permission assignments
- View role details and assigned users

### Permission Management (`/ims/permissions`)
- View all system permissions
- Create custom permissions
- Edit permission details
- View which roles have specific permissions

### User Management (`/ims/users`)
- Assign roles to users during creation/editing
- View user roles and permissions
- Manage user access levels

## Database Structure

### Tables
- `roles` - Stores role information
- `permissions` - Stores permission information
- `role_has_permissions` - Many-to-many relationship between roles and permissions
- `model_has_roles` - Polymorphic relationship for user-role assignments
- `model_has_permissions` - Polymorphic relationship for direct user permissions

### Migration and Seeding

Run migrations and seed default data:
```bash
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=AdminSeeder
```

## Security Notes

1. **Super Admin Protection**: Be careful when assigning Super Admin role
2. **Permission Hierarchy**: Users inherit permissions from their roles
3. **Route Protection**: All sensitive routes are protected with appropriate middleware
4. **Default Permissions**: New features should include appropriate permission checks

## Navigation

The navigation system automatically shows/hides menu items based on user permissions:
- Users only see modules they have access to
- Admin sections are hidden from regular users
- Role-based sections are organized logically

## Testing

Run RBAC tests to ensure proper functionality:
```bash
php artisan test tests/Feature/RBACTest.php
```

The test suite covers:
- Role and permission creation
- User role assignments
- Permission inheritance
- Access control validation
- Default data seeding