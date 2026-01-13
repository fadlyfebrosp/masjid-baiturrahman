<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlokasiDonasi extends Model
{
    protected $table = 'alokasi_donasi';

    protected $fillable = [
        'program_id',
        'nama_kegiatan',
        'keterangan',
        'jumlah',
        'tanggal',
        'created_by'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function pengeluaran()
    {
        return $this->hasOne(Pengeluaran::class);
    }
}
