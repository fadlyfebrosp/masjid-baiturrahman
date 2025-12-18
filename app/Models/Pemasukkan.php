<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukkan extends Model
{
    /** @use HasFactory<\Database\Factories\PemasukkanFactory> */
    use HasFactory;
    protected $fillable = [
        'tanggal',
        'sumber_dana',
        'jumlah_dana',
        'keterangan'
    ];
    public function getJumlahDanaFormatAttribute()
    {
        $angka = $this->jumlah_dana;

        if ($angka >= 1000000000) {
            return number_format($angka / 1000000000, 2) . ' Miliar';
        } elseif ($angka >= 1000000) {
            return number_format($angka / 1000000, 2) . ' Juta';
        } elseif ($angka >= 1000) {
            return number_format($angka / 1000, 2) . ' Ribu';
        }

        return number_format($angka, 2);
    }
}
