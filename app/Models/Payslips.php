<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslips extends Model
{
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

    use HasFactory;
}
