<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNote extends Model
{
    use HasFactory;
    protected $table = 'credit_notes';

    protected $fillable = [
        'type',
        'status',
        'description',
        'user_id',
        'amount',
        'user_id',
        'is_credit',
        'is_active',
        'invoice_id',
        'is_applied'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function lines()
    {
        return $this->hasMany(CreditNoteLine::class);
    }
}
