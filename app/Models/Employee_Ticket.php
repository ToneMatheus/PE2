<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee_Ticket extends Model
{
    use HasFactory;
    protected $table = 'employee_tickets';

    protected $fillable = [
        'employee_profile_id',
        'ticket_id',
        'assigned_date'
    ];

    use HasFactory;
    public $timestamps = false;

    public function employee_profile(): BelongsTo
    {
        return $this->belongsTo(Employee_Profile::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
