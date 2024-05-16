<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_name'
    ];

    public function user_roles(): HasMany
    {
        return $this->hasMany(User_Role::class);
    }

    public function employee_benefits(): HasMany
    {
        return $this->hasMany(EmployeeBenefit::class);
    }

    public function salary_ranges(): HasMany
    {
        return $this->hasMany(SalaryRange::class);
    }

    public function employee_contracts(): HasMany
    {
        return $this->hasMany(Employee_contract::class);
    }
}
