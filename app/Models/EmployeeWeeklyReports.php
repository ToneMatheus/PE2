<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeWeeklyReports extends Model
{
    use HasFactory;

    protected $table = 'employee_weekly_reports';
    protected $fillable = [
        'employee_profile_id',
        'summary',
        'tasks_completed',
        'upcoming_tasks',
        'challenges',
        'submission_date'
    ];

    public function employee_profile(): BelongsTo
    {
        return $this->belongsTo(Employee_Profile::class);
    }
}
