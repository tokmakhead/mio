<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mioly.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'demo_readonly' => false,
        ]);

        // Demo user (read-only)
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@mioly.test',
            'password' => Hash::make('password'),
            'role' => 'user',
            'demo_readonly' => true,
        ]);
    }
}
