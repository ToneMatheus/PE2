<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CronJobRunLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cron_job_run_id',
        'invoice_id',
        'log_level',
        'message',
    ];

    public function cron_job_run(): BelongsTo
    {
        return $this->belongsTo(CronJobRun::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
