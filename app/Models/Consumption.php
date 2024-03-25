<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'consumption_value',
        'prev_index_id',
        'current_index_id'
    ];
}
