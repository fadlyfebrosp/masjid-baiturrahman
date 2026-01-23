<?php

namespace Database\Factories;

use App\Models\BeritaFoto;
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
            'kategori' => $this->faker->randomElement(['Berita', 'Kegiatan']),
            'deskripsi' => $this->faker->paragraph(5),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function ($berita) {
            BeritaFoto::factory(rand(1, 3))->create([
                'berita_dan_kegiatan_id' => $berita->id,
            ]);
        });
    }
}
