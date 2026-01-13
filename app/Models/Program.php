<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Program extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $appends = [
        'foto_url',
        'sisa_hari',
    ];
    public const SUB_ZAKAT = [
        'fitrah'     => 'Zakat Fitrah',
        'mal'        => 'Zakat Penghasilan / Mal',
        'emas'       => 'Zakat Emas',
        'pertanian'  => 'Zakat Pertanian',
        'peternakan' => 'Zakat Peternakan',
    ];
    protected $fillable = [
        'kategori',
        'sub_kategori',
        'judul',
        'slug',
        'min_donasi',
        'custom_nominal',
        'target_dana',
        'target_waktu',
        'open_goals',
        'foto',
        'deskripsi',
    ];

    protected $casts = [
        'custom_nominal' => 'array',
        'open_goals' => 'boolean',
        'target_dana' => 'integer',
        'min_donasi' => 'integer',
        'target_waktu' => 'date',
    ];

    // full url accessor
    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : asset('assets/img/doa.jpeg');
    }

    // Relasi: 1 program punya banyak donasi
    public function donasis()
    {
        return $this->hasMany(Donasi::class);
    }
    public function alokasiDonasi()
    {
        return $this->hasMany(AlokasiDonasi::class);
    }

    protected static function booted()
    {
        static::creating(function ($program) {
            $program->slug = self::generateSlug($program->judul);
        });

        static::updating(function ($program) {
            if ($program->isDirty('judul')) {
                $program->slug = self::generateSlug($program->judul);
            }
        });
    }

    public static function generateSlug($judul)
    {
        $base = Str::slug($judul);
        $count = Program::where('slug', 'LIKE', "$base%")->count();

        return $count ? "{$base}-{$count}" : $base;
    }
    public function getSisaHariAttribute()
    {
        if ($this->open_goals || !$this->target_waktu) {
            return 'Tanpa Batas Waktu';
        }

        $hari = now()->startOfDay()->diffInDays(
            \Carbon\Carbon::parse($this->target_waktu)->startOfDay(),
            false
        );

        return $hari > 0 ? $hari.' Hari' : 'Berakhir';
    }
    public function getSubKategoriLabelAttribute(): ?string
    {
        if (!$this->sub_kategori) {
            return null;
        }

        return self::SUB_ZAKAT[$this->sub_kategori]
            ?? ucfirst($this->sub_kategori);
    }
}
