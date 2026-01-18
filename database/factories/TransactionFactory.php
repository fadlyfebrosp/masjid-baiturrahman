<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Donasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'donasi_id'      => Donasi::factory(),
            'reference'      => 'DON-' . time(),
            'payment_method' => null,
            'amount'         => 100000,
            'status'         => 'pending',
        ];
    }
}
