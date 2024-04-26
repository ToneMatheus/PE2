<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer_Address extends Model
{
    public $timestamps = false;
    
    use HasFactory;
    protected $table = 'customer_addresses';

    protected $fillable = [
        'start_date',
        'end_date',
        'user_id',
        'address_id'
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
