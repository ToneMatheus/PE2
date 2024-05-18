<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee_contract extends Model
{
    protected $table = 'employee_contracts';
    protected $fillable = [
        'employee_profile_id',
        'start_date',
        'end_date',
        'type',
        'status',
        'role_id',
        'salary_range_id',
        'benefits_id'
    ];

    use HasFactory;

    public function employee_profile(): BelongsTo
    {
        return $this->belongsTo(Employee_Profile::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function salary_range(): BelongsTo
    {
        return $this->belongsTo(SalaryRange::class);
    }

    public function employee_benefit(): BelongsTo
    {
        return $this->belongsTo(EmployeeBenefit::class);
    }
}
