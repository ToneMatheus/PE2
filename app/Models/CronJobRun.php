<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJobRun extends Model
{
    protected $fillable = [
        'id',
        'name', 
        'started_at', 
        'ended_at', 
        'status', 
        'error_message'
    ];
}
