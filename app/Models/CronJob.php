<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'scheduled_day',
        'scheduled_time'
    ];
}