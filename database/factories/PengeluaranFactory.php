<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PengeluaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tanggal' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'kategori' => $this->faker->randomElement([
                'Listrik & Air',
                'Honor Marbot',
                'Santunan Sosial',
                'Kegiatan Dakwah',
                'Renovasi Masjid',
                'Operasional',
            ]),
            'jumlah_dana' => $this->faker->numberBetween(100_000, 8_000_000),
            'keterangan'  => $this->faker->optional()->sentence(),
        ];
    }
}
