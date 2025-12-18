<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profiladmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'gender', 'image'
    ];
}
