<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_profile_id',
        'holiday_type_id',
        'yearly_holiday_credit',
        'user_holiday_credit',
        'start_date',
        'end_date'
    ];
}
