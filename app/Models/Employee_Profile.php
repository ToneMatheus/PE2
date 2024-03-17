<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_Profile extends Model
{
    use HasFactory;

    public const VALIDATION_RULE_JOB = ['required'];
    public const VALIDATION_RULE_HIRE_DATE = ['required'];
    public const VALIDATION_RULE_DEPARTMENT = ['required'];

    public const VALIDATION_RULES = [
        'job',
        'hire_date',
        'department'
    ];

    protected $fillable = [
        'id',
        'job',
        'hire_date',
        'department',
        'notes',
        'nationality',
        'sex'
    ];

    protected $table = 'employee_profiles';
    protected $primaryKey = 'id';
    
    public static function validate(array $input): bool
    {
        $rules = static::VALIDATION_RULES;

        return \Illuminate\Support\Facades\Validator::make($input, $rules)->passes();
    }
}
