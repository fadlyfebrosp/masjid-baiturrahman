<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class KontakInformasi extends Model
{
    use HasFactory;

    protected $table = 'kontakinformasis';

    protected $fillable = [
        'nama_aplikasi',
        'alamat_lengkap',
        'email',
        'nomor_telepon',
        'nomor_whatsapp',
        'logo',
    ];
}
