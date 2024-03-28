<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    protected $fillable = [
        'name',
        'interval', 
        'scheduled_day',
        'scheduled_month',
        'scheduled_time',
        'is_enabled'
    ];

    protected $attributes = [
        'is_enabled' => true,
    ];
}