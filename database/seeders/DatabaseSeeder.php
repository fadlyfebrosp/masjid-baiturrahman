<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BeritadankegiatanSeeder::class,
            ProgramSeeder::class,
            PemasukkanSeeder::class,
            PengeluaranSeeder::class,
        ]);
    }
}
