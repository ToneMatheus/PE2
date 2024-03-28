<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            // 'birth_date' => self::VALIDATION_RULE_BIRTHDATE,
    ];

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'password',
        'employee_profile_id',
        'is_company',
        'company_name',
        'phone_nbr',
        'birth_date',
        'is_active',
        'email',
        'title'
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


    //! de onderstaande functie zou moeten toe gevoegd worden. afhenkelijk van wat er gedaan wordt met het role systeem.
    // public function hasRole($role)
    // {
    //     return $this->role === $role;
    // }

    public function team_members(): HasMany
    {
        return $this->hasMany(Team_Member::class);
    }  

    public function user_roles(): HasMany
    {
        return $this->hasMany(User_Role::class);
    }  

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }  
    
    public function employee_profile(): BelongsTo
    {
        return $this->belongsTo(Employee_Profile::class);
    }
}
