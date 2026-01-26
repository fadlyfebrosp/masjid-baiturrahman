<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaFoto extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'berita_dan_kegiatan_id',
        'path',
    ];

    public function berita()
    {
        return $this->belongsTo(
            \App\Models\BeritaDanKegiatan::class,
            'berita_dan_kegiatan_id'
        );
    }
}
