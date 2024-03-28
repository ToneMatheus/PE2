<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Holiday_Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];

    public function balances(): HasMany
    {
        return $this->hasMany(Balance::class);
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class);
    }
}
