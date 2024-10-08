<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'dummy@example.com',
            'password' => 'password', // Use Hash to securely store the password
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
