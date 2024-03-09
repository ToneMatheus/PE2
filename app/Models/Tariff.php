<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = [
        'type',
        'range_min',
        'range_max',
        'rate'
    ];

    use HasFactory;
}
