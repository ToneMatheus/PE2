<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_profile_id',
        'start_date',
        'end_date',
        'holiday_type_id',
        'reason',
        'file_location',
        'manager_approval',
        'boss_approval',
        'is_active'
    ];

    public function employee_profile(): BelongsTo
    {
        return $this->belongsTo(Employee_Profile::class);
    }

    public function holiday_type(): BelongsTo
    {
        return $this->belongsTo(Holiday_Type::class);
    }
}
