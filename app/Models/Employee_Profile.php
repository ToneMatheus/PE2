<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_Profile extends Model
{
    protected $fillable = [
        'job',
        'hire_date',
        'department',
        'notes',
        'nationality',
        'sex'
    ];

    use HasFactory;
}
