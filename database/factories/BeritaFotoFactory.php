<?php

namespace Database\Factories;

use App\Models\BeritaFoto;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeritaFotoFactory extends Factory
{
    protected $model = BeritaFoto::class;

    public function definition(): array
    {
        return [
            'path' => 'berita-foto/dummy.jpg',
        ];
    }
}
