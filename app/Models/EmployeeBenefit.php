<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefit_name',
        'description',
        'role_id'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function employee_contracts(): HasMany
    {
        return $this->hasMany(Employee_contract::class);
    }
}
