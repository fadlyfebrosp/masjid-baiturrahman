<?php

namespace Database\Factories;

use App\Models\Contactdonasioffline;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactdonasiofflineFactory extends Factory
{
    protected $model = Contactdonasioffline::class;

    public function definition(): array
    {
        return [
            'name'   => $this->faker->name,
            'email'  => $this->faker->unique()->safeEmail,
            'phone'  => '08' . $this->faker->randomNumber(9),
            'gender' => 'male',
        ];
    }
}
