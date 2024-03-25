<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslips extends Model
{
    protected $fillable = [
        'employee_profile_id',
        'start_date',
        'end_date',
        'creation_date',
        'nbr_days_worked',
        'total_hours',
        'IBAN',
        'amount_per_hour'
    ];

    use HasFactory;
}
