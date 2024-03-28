<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public $timestamps = false;

    use HasFactory;
}
