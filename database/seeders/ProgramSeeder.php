<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        Program::factory()->count(3)->fitrah()->create();
        Program::factory()->count(3)->mal()->create();
        Program::factory()->count(3)->emas()->create();
        Program::factory()->count(3)->pertanian()->create();
        Program::factory()->count(3)->peternakan()->create();
    }
}
