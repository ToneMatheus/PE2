<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    protected $fillable = [
        'nbr_occupants',
        'is_home_all_day',
        'heat_with_power',
        'water_with_power',
        'cook_with_power',
        'nbr_air_con',
        'nbr_fridges',
        'nbr_washers',
        'nbr_computers',
        'nbr_entertainment',
        'nbr_dishwashers',
        'estimation_total',
        'meter_id'
    ];

    use HasFactory;
}
