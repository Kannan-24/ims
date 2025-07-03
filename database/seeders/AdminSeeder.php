<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Radhika R',
            'email' => 'kannanmuruganandham1@gmail.com',
            'password' => Hash::make('SKM@123'),
            'employee_id' => 'SKME001',
            'phone' => '6382511495',
            'address' => '219/5/195, Mariamman Kovil Street, Kn Colony',
            'blood_group' => 'O+',
            'state' => 'Tamil Nadu',
            'gender' => 'Female',
            'dob' => '1981-03-22',
            'doj' => '2008-01-01',
            'role' => 'Admin',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign Super Admin role if it exists
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $admin->assignRole($superAdminRole);
        }

        // Create a test user with Employee role
        $employee = User::create([
            'name' => 'Test Employee',
            'email' => 'employee@test.com',
            'password' => Hash::make('SKM@123'),
            'employee_id' => 'SKME002',
            'phone' => '9876543210',
            'address' => 'Test Address',
            'blood_group' => 'A+',
            'state' => 'Tamil Nadu',
            'gender' => 'Male',
            'dob' => '1990-01-01',
            'doj' => '2023-01-01',
            'role' => 'Employee',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign Employee role if it exists
        $employeeRole = Role::where('name', 'Employee')->first();
        if ($employeeRole) {
            $employee->assignRole($employeeRole);
        }
    }
}
