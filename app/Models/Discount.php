<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    protected $fillable = [
        'contract_product_id',
        'range_min',
        'range_max',
        'rate',
        'start_date',
        'end_date'
    ];

    public function contract_product(): BelongsTo
    {
        return $this->belongsTo(Contract_product::class);
    }

    use HasFactory;
}
