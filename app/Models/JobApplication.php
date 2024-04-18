<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $table = 'job_applications';
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'profile',
        'job_id',
        'is_hired',
        'start_date',
        'end_date'
    ];

    public function job_offer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class);
    }
}
