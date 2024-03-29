<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meter_Addresses extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'address_id',
        'meter_id'
    ];

    use HasFactory;

    public function meter(): BelongsTo
    {
        return $this->belongsTo(Meter::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
