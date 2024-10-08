<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a dummy employee
        Employee::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'middle' => 'Smith',
            'suffix' => null,
            'age' => 30,
            'address' => '123 Main St, City, Country',
            'contact_number' => '123-456-7890',
            'gender' => 'Male',
            'role' => 'Cashier',
            'username' => 'johndoe1',
            'password' => bcrypt('password'), // Use bcrypt to hash the password
            'status' => 'active',
            'avatar' => null, // You can specify an avatar file path or leave it null
        ]);
    }
}
