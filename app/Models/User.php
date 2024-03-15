<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const VALIDATION_RULE_USERNAME = ['required'];
    public const VALIDATION_RULE_FIRST_NAME = ['required'];
    public const VALIDATION_RULE_LAST_NAME = ['required'];
    public const VALIDATION_RULE_PASSWORD = ['required'];
    public const VALIDATION_RULE_EMAIL = ['required'];
    public const VALIDATION_RULE_PHONE_NBR = ['required'];
    public const VALIDATION_RULE_BIRTHDATE = ['required'];

    public const VALIDATION_RULES = [
            'username' => self::VALIDATION_RULE_USERNAME,
            'first_name' => self::VALIDATION_RULE_FIRST_NAME,
            'last_name' => self::VALIDATION_RULE_LAST_NAME,
            'password' => self::VALIDATION_RULE_PASSWORD,
            'email' => self::VALIDATION_RULE_EMAIL,
            'phone_nbr' => self::VALIDATION_RULE_PHONE_NBR,
            'birth_date' => self::VALIDATION_RULE_BIRTHDATE,
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'username',
        'first_name',
        'last_name',
        'password',
        'address_id',
        'employee_profile_id',
        'is_company',
        'company_name',
        'email',
        'phone_nbr',
        'birth_date',
        'is_active'
    ];

    public static function validate(array $input): bool
    {
        $rules = static::VALIDATION_RULES;

        return \Illuminate\Support\Facades\Validator::make($input, $rules)->passes();
    }
    
 
    public function creditNotes()
    {
        return $this->hasMany(CreditNote::class);
    }

}
