<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BeritadankegiatanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(4),
            'namamasjid' => 'Masjid Baiturrahman',
            'tanggal' => $this->faker->date(),
            'kategori' => $this->faker->randomElement(['Kegiatan', 'Berita', 'Donasi']),
            'foto' => null,
            'deskripsi' => $this->faker->paragraph(5),
        ];
    }
}
