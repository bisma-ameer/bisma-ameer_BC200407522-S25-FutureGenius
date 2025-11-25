<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Check if admin already exists
        $adminExists = User::where('email', 'admin@kidicode.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@kidicode.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0300-1234567',
            ]);
            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}