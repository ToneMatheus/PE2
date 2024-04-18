<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    public $timestamps = false;
    
    use HasFactory;

    public const VALIDATION_RULE_STREET = ['required', 'string', 'max:50'];
    public const VALIDATION_RULE_NBR = ['required'];
    public const VALIDATION_RULE_BOX = ['required', 'string', 'max:4'];
    public const VALIDATION_RULE_POSTAL_CODE = ['required'];
    public const VALIDATION_RULE_CITY = ['required', 'string', 'max:50'];
    public const VALIDATION_RULE_PROVINCE = ['required', 'string', 'max:50'];
    public const VALIDATION_RULE_COUNTRY = ['required', 'string', 'max:50'];
    public const VALIDATION_RULE_TYPE = ['required'];

    public const VALIDATION_MESSAGES = [
        'street.required' => 'Street is required',
        'street.string' => 'Street must be a string',
        'street.max' => 'Street has a max of 50 character',
        'number.required' => 'Number is required',
        'box.required' => 'Box is required',
        'box.string' => 'Box must be a string',
        'box.max' => 'Box has a max of 4 character',
        'postal_code.required' => 'Postal code is required',
        'city.required' => 'City is required',
        'city.string' => 'City must be a string',
        'city.max' => 'City has a max of 50 character',
        'province.required' => 'Province is required',
        'province.string' => 'Province must be a string',
        'province.max' => 'Province has a max of 50 character',
        'country.required' => 'Country is required',
        'country.string' => 'Country must be a string',
        'country.max' => 'Country has a max of 50 character',
        'type.required' => 'Type is required',
    ];

    public const VALIDATION_RULES = [
        'street' => self::VALIDATION_RULE_STREET,
        'number' => self::VALIDATION_RULE_NBR,
        'box' => self::VALIDATION_RULE_BOX,
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
