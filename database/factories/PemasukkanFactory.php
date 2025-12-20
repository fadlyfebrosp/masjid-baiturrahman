<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PemasukkanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tanggal'     => $this->faker->dateTimeBetween('-3 months', 'now'),
            'sumber_dana' => $this->faker->randomElement([
                'Donasi Jamaah',
                'Kotak Amal Jumat',
                'Zakat',
                'Infak',
                'Sedekah',
                'Donatur Tetap',
            ]),
            'jumlah_dana' => $this->faker->numberBetween(100_000, 10_000_000),
            'keterangan'  => $this->faker->optional()->sentence(),
        ];
    }
}
