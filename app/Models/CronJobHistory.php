<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJobHistory extends Model
{
    protected $fillable = [
        'job_name', 
        'status',      
        'completed_at'
    ];
}
