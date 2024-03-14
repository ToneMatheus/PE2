<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslips extends Model
{
    protected $fillable = [
        'startDate',
        'endDate',
        'creationDate',
        'nrDaysWorked',
        'totalHours',
        'IBAN',
        'amountPerHour',
        'employeeID',
    ];

    use HasFactory;
}
