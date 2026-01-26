<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaDanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'beritadankegiatan';

    public $timestamps = false;

    protected $fillable = [
        'judul',
        'namamasjid',
        'tanggal',
        'kategori',
        'deskripsi',
    ];

    public function fotos()
    {
        return $this->hasMany(
            \App\Models\BeritaFoto::class,
            'berita_dan_kegiatan_id'
        );
    }
}
