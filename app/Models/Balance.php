<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_profile_id',
        'holiday_type_id',
        'yearly_holiday_credit',
        'user_holiday_credit',
        'start_date',
        'end_date',
        'sick_days'
    ];

    public $timestamps = false;

    public function employee_profile(): BelongsTo
    {
        return $this->belongsTo(Employee_Profile::class);
    }

    public function holiday_type(): BelongsTo
    {
        return $this->belongsTo(Holiday_Type::class);
    }
}
