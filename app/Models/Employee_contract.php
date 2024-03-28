<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee_contract extends Model
{
    protected $fillable = [
        'employee_profile_id',
        'start_date',
        'end_date',
        'type',
        'status',
        'salary_per_month'
    ];

    use HasFactory;

    public function employee_profile(): BelongsTo
    {
        return $this->belongsTo(Employee_Profile::class);
    }
}
