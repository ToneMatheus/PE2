<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CronJobRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name', 
        'started_at', 
        'ended_at', 
        'status', 
        'error_message'
    ];

    public function cron_job_run_logs(): HasMany
    {
        return $this->hasMany(CronJobRunLog::class);
    } 
}
