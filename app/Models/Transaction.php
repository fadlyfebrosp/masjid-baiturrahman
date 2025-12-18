<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'donasi_id',
        'reference',
        'payment_method',
        'payment_code',
        'pay_url',
        'amount',
        'status',
        'paid_at'
    ];

    // Relasi ke Donasi
    public function donasi()
    {
        return $this->belongsTo(Donasi::class);
    }
}
