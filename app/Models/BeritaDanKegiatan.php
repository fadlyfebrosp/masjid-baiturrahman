<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaDanKegiatan extends Model
{
    protected $table = 'beritadankegiatan';

    protected $fillable = [
        'judul',
        'namamasjid',
        'tanggal',
        'kategori',
        'deskripsi',
        'foto'
    ];
}
