<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslips extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_profile_id',
        'startDate',
        'endDate',
        'creationDate',
        'nrDaysWorked',
        'totalHours',
        'IBAN',
        'amountPerHour',
    ];

    public function employee_profile(): BelongsTo
    {
        return $this->belongsTo(Employee_Profile::class);
    }
}
