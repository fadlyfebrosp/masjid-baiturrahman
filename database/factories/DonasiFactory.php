<?php

namespace Database\Factories;

use App\Models\Donasi;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonasiFactory extends Factory
{
    protected $model = Donasi::class;

    public function definition()
    {
        return [
            'program_id'   => Program::factory(),
            'nama_donatur' => $this->faker->name,
            'email'        => $this->faker->safeEmail,
            'telepon'      => $this->faker->phoneNumber,
            'anonim'       => false,
            'nominal'      => 100000,
            'status'       => 'pending',
        ];
    }
}
