<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // First delete existing records if we run seed refresh, except if we want to run incrementally, 
        // but DB::table('users')->insert could crash if emails duplicate. We'll use insertOrIgnore or firstOrCreate 
        // to ensure the defaults are cleanly created.
        
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '08123456789',
                'email_verified_at' => now(),
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '08987654321',
                'email_verified_at' => now(),
            ]
        );

        // Generate 50 Users
        \App\Models\User::factory(50)->create([
            'role' => 'user'
        ]);

        // Generate 20 Admins
        \App\Models\User::factory(20)->create([
            'role' => 'admin'
        ]);
    }
}