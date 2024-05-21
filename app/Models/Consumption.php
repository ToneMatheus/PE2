<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'consumption_value',
        'prev_index_id',
        'current_index_id'
    ];

    public function invoice_lines(): HasMany
    {
        return $this->hasMany(Invoice_line::class);
    }

    public function prev_index_value(): BelongsTo
    {
        return $this->belongsTo(Index_Value::class);
    }

    public function current_index_value(): BelongsTo
    {
        return $this->belongsTo(Index_Value::class);
    }
}
