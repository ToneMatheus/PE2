<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJobRunLog extends Model
{
    protected $fillable = [
        'cron_job_run_id',
        'invoice_id',
        'log_level',
        'message',
    ];
}
