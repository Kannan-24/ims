<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Radhika R',
            'email' => 'kannanmuruganandham1@gmail.com',
            'password' => Hash::make('12345678'),
            'employee_id' => 'SKME01',
            'phone' => '6382511495',
            'address' => '219/5/195, Mariamman Kovil Street, Kn Colony',
            'blood_group' => 'O+',
            'state' => 'Tamil Nadu',
            'gender' => 'Female',
            'dob' => '1981-03-22',
            'doj' => '2008-01-01',
            'designation' => 'Admin',
            'role' => 'admin',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
