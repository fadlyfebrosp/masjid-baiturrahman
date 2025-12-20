<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        $judul = ucfirst($this->faker->sentence(4));
        $openGoals = $this->faker->boolean(30);

        return [
            'kategori'       => 'Zakat', // default zakat
            'sub_kategori'   => null,    // default null (di-set via state)
            'judul'          => $judul,
            'min_donasi'     => $this->faker->randomElement([10000, 20000, 50000]),
            'custom_nominal' => [10000, 25000, 50000, 100000],
            'target_dana'    => $openGoals ? null : $this->faker->numberBetween(5_000_000, 50_000_000),
            'target_waktu'   => $openGoals ? null : Carbon::now()->addDays(rand(10, 120)),
            'open_goals'     => $openGoals,
            'foto'           => null,
            'deskripsi'      => $this->faker->paragraphs(3, true),
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | STATES SUB KATEGORI ZAKAT
    |--------------------------------------------------------------------------
    */

    public function fitrah()
    {
        return $this->state(fn () => [
            'sub_kategori' => 'fitrah',
            'judul'        => 'Zakat Fitrah ' . ucfirst($this->faker->sentence(3)),
        ]);
    }

    public function mal()
    {
        return $this->state(fn () => [
            'sub_kategori' => 'mal',
            'judul'        => 'Zakat Penghasilan ' . ucfirst($this->faker->sentence(3)),
        ]);
    }

    public function emas()
    {
        return $this->state(fn () => [
            'sub_kategori' => 'emas',
            'judul'        => 'Zakat Emas ' . ucfirst($this->faker->sentence(3)),
        ]);
    }

    public function pertanian()
    {
        return $this->state(fn () => [
            'sub_kategori' => 'pertanian',
            'judul'        => 'Zakat Pertanian ' . ucfirst($this->faker->sentence(3)),
        ]);
    }

    public function peternakan()
    {
        return $this->state(fn () => [
            'sub_kategori' => 'peternakan',
            'judul'        => 'Zakat Peternakan ' . ucfirst($this->faker->sentence(3)),
        ]);
    }
}
