<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't create if already exists
        if (User::where('email', 'master@mionex.com')->exists()) {
            $this->command->info('Master Admin already exists.');
            return;
        }

        User::create([
            'name' => 'Super Master',
            'email' => 'master@mionex.com',
            'password' => Hash::make('mionexMaster123'),
            'is_master' => true,
            'master_role' => 'super_admin',
            'role' => 'admin',
        ]);

        $this->command->info('Master Admin created successfully: master@mionex.com / mionexMaster123');
    }
}
