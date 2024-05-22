<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meter_Reader_Schedule extends Model
{
    protected $table = 'meter_reader_schedules';
    protected $fillable = [
        'employee_profile_id',
        'reading_date',
        'meter_id',
        'status'
    ];

    use HasFactory;

    public function meter(): BelongsTo
    {
        return $this->belongsTo(Meter::class);
    }

    public function employee_profile(): BelongsTo
    {
        return $this->belongsTo(Employee_Profile::class);
    }
}
