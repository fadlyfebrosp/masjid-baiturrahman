<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemasukkan;

class PemasukkanSeeder extends Seeder
{
    public function run(): void
    {
        Pemasukkan::factory()->count(40)->create();
    }
}
