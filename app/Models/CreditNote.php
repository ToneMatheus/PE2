<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'amount',
        'customer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    use HasFactory;
}
