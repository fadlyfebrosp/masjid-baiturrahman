<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactdonasioffline extends Model
{
    use HasFactory;

    protected $table = 'contactdonasiofflines';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'gender',
        'country',
        'province',
        'city',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

