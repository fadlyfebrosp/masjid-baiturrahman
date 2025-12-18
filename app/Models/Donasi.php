<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;
    protected $table = 'donasis';

    protected $fillable = [
        'program_id',
        'user_id',
        'nama_donatur',
        'email',
        'telepon',
        'anonim',
        'nominal',
        'deskripsi',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
