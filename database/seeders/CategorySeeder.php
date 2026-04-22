<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Infrastruktur', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kebersihan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kependudukan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bantuan Sosial', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keamanan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kesehatan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pendidikan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pemerintah Desa', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}