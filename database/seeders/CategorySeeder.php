<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori')->insert([
            ['nama' => 'Infrastruktur',   'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kebersihan',      'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kependudukan',    'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Bantuan Sosial',  'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Keamanan',        'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kesehatan',       'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pendidikan',      'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pemerintah Desa', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
