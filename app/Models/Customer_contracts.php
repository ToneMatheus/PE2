<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer_contracts extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'start_date',
        'end_date',
        'type',
        'price',
        'status'
    ];
//meerdere contract products

    public function contract_products(): HasMany
    {
        return $this->hasMany(Contract_product::class,'id');
    }
    
    use HasFactory;
}
