<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    public const VALIDATION_RULE_STREET = ['required'];
    public const VALIDATION_RULE_NBR = ['required'];
    public const VALIDATION_RULE_POSTAL_CODE = ['required'];
    public const VALIDATION_RULE_CITY = ['required'];
    public const VALIDATION_RULE_PROVINCE = ['required'];
    public const VALIDATION_RULE_COUNTRY = ['required'];
    public const VALIDATION_RULE_TYPE = ['required'];

    public const VALIDATION_RULES = [
        'street' => self::VALIDATION_RULE_STREET,
        'number' => self::VALIDATION_RULE_NBR,
        'postal_code' => self::VALIDATION_RULE_POSTAL_CODE,
        'city' => self::VALIDATION_RULE_CITY,
        'province' => self::VALIDATION_RULE_PROVINCE,
        'country' => self::VALIDATION_RULE_COUNTRY,
        'type' => self::VALIDATION_RULE_TYPE,
    ];

    protected $table = 'addresses';
    protected $primaryKey = 'id';

    /*public function user()
    {
        return $this->belongsTo(User::class);
    }*/

    public function customer_addresses(): HasMany
    {
        return $this->hasMany(Customer_Address::class);
    }  

    public function meter_addresses(): HasMany
    {
        return $this->hasMany(Meter_Addresses::class);
    }  

    protected $fillable = [
        'id',
        'street',
        'number',
        'box',
        'postal_code',
        'city',
        'province',
        'country',
        'type',
        'is_billing_address'
    ];

    public static function validate(array $input): bool
    {
        $rules = static::VALIDATION_RULES;

        return \Illuminate\Support\Facades\Validator::make($input, $rules)->passes();
    }
}
