<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tariff extends Model
{
    protected $fillable = [
        'type',
        'range_min',
        'range_max',
        'rate'
    ];

    public function product_tariffs(): HasMany
    {
        return $this->hasMany(Product_tariff::class);
    }

    /*public function contract_products(): HasMany
    {
        return $this->hasMany(Contract_product::class);
    }*/

    use HasFactory;
}
