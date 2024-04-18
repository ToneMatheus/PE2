<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'description',
        'start_date',
        'end_date',
        'type'
    ];

    public function product_tariffs(): HasMany
    {
        return $this->hasMany(Product_tariff::class);
    }

    public function contract_products(): HasMany
    {
        //return $this->hasMany(Contract_product::class);
        return $this->hasMany(Contract_product::class, 'product_id');
    }    

    use HasFactory;
}
