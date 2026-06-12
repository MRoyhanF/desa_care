<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama'                     => 'Admin',
                'kata_sandi'               => Hash::make('password123'),
                'peran'                    => 'admin',
                'telepon'                  => '08123456789',
                'email_terverifikasi_pada' => now(),
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'nama'                     => 'Pengguna',
                'kata_sandi'               => Hash::make('password123'),
                'peran'                    => 'pengguna',
                'telepon'                  => '08987654321',
                'email_terverifikasi_pada' => now(),
            ]
        );

        \App\Models\User::factory(50)->create(['peran' => 'pengguna']);
        \App\Models\User::factory(20)->create(['peran' => 'admin']);
    }
}
