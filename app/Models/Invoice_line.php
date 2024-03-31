<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice_line extends Model
{
    protected $table = 'invoice_lines';
    protected $fillable = [
        'type',
        'unit_price',
        'amount',
        'consumption_id',
        'invoice_id'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function consumption(): BelongsTo
    {
        return $this->belongsTo(Consumption::class);
    }

    use HasFactory;
}
