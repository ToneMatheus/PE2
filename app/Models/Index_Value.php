<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Index_Value extends Model
{
    use HasFactory;
    protected $table = 'index_values';

    protected $fillable = [
        'reading_date',
        'reading_value',
        'meter_id'
    ];

    public function meter(): BelongsTo
    {
        return $this->belongsTo(Meter::class);
    }

    public function prev_consumptions(): HasMany
    {
        return $this->hasMany(Consumption::class, 'prev_index_id');
    }  

    public function current_consumptions(): HasMany
    {
        return $this->hasMany(Consumption::class, 'current_index_id');
    }  
}
