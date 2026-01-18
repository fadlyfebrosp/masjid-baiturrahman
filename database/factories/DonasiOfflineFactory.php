<?php

namespace Database\Factories;

use App\Models\DonasiOffline;
use App\Models\Program;
use App\Models\Contactdonasioffline;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonasiOfflineFactory extends Factory
{
    protected $model = DonasiOffline::class;

    public function definition(): array
    {
        return [
            'program_id' => Program::factory(),
            'contactdonasioffline_id' => Contactdonasioffline::factory(),
            'nominal' => 100000,
            'metode_pembayaran' => 'CASH',
            'tanggal_transaksi' => now(),
            'kode_transaksi' => $this->faker->unique()->uuid(),
            'status' => 'SELESAI',
        ];
    }
}
