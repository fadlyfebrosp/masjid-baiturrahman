<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonasiOffline extends Model
{
    protected $fillable = [
        'program_id',
        'contactdonasioffline_id',
        'nominal',
        'metode_pembayaran',
        'tanggal_transaksi',
        'kode_transaksi',
        'catatan',
        'status',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contactdonasioffline::class, 'contactdonasioffline_id');
    }
}
