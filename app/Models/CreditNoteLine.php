<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditNoteLine extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'credit_note_id',
        'product',
        'quantity',
        'price',
        'amount',
    ];

    public function creditNote()
    {
        return $this->belongsTo(CreditNote::class);
    }
}
