<?php

namespace Database\Factories;

use App\Models\BeritaDanKegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeritaDankegiatanFactory extends Factory
{
    protected $model = BeritaDanKegiatan::class;

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
