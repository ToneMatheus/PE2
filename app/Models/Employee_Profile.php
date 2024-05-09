<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee_Profile extends Model
{
    use HasFactory;

    //public const VALIDATION_RULE_JOB = ['required'];
    public const VALIDATION_RULE_HIRE_DATE = ['required'];
    //public const VALIDATION_RULE_DEPARTMENT = ['required'];

    public const VALIDATION_RULES = [
        'job',
        'hire_date'
    ];

    protected $fillable = [
        'id',
        'job',
        'hire_date',
        'notes',
        'line_number'
    ];

    public $timestamps = false;
    protected $table = 'employee_profiles';
    protected $primaryKey = 'id';
    
    public static function validate(array $input): bool
    {
        $rules = static::VALIDATION_RULES;

        return \Illuminate\Support\Facades\Validator::make($input, $rules)->passes();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function balances(): HasMany
    {
        return $this->hasMany(Balance::class);
    }

    public function meter_reader_schedules(): HasMany
    {
        return $this->hasMany(Meter_Reader_Schedule::class);
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class);
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslips::class);
    }

    public function employee_contracts(): HasMany
    {
        return $this->hasMany(Employee_contract::class);
    }

    public function employee_tickets(): HasMany
    {
        return $this->hasMany(Employee_Ticket::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
