<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Mail\ConfirmationMailRegistration;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    public const VALIDATION_RULE_USERNAME = ['string', 'max:255', 'required'];
    public const VALIDATION_RULE_FIRST_NAME = ['required'];
    public const VALIDATION_RULE_LAST_NAME = ['required'];
    public const VALIDATION_RULE_PASSWORD = ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'];
    public const VALIDATION_RULE_EMAIL = ['required', 'email'];
    public const VALIDATION_RULE_PHONE_NBR = ['required'];
    public const VALIDATION_RULE_BIRTHDATE = ['required', 'date', 'before:-18 years', 'after:-150 years'];

    public const VALIDATION_MESSAGES = [
        'username.required' => 'Username is required',
        'first_name.required' => 'First name is required',
        'last_name.required' => 'Last name is required',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 8 characters long',
        'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number',
        'email.required' => 'Email is required',
        'email.email' => 'Invalid email format',
        'email.unique' => 'Email already exists',
        'phone_nbr.required' => 'Phone number is required',
        'birth_date.required' => 'Birth date is required',
        'birth_date.date' => 'Invalid date format',
        'birth_date.before' => 'You need to be older. At least 18 years old',
        'birth_date.after' => 'Birth date is too old',
    ];

    public const VALIDATION_RULES = [
            'username' => self::VALIDATION_RULE_USERNAME,
            'first_name' => self::VALIDATION_RULE_FIRST_NAME,
            'last_name' => self::VALIDATION_RULE_LAST_NAME,
            'password' => self::VALIDATION_RULE_PASSWORD,
            'email' => self::VALIDATION_RULE_EMAIL,
            'phone_nbr' => self::VALIDATION_RULE_PHONE_NBR,
            'birth_date' => self::VALIDATION_RULE_BIRTHDATE,
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

    //TEST dit mag weg als van ProfileController lijn 53 - 60 werkt.
    // public function saveWithoutEmail()
    // {
    //     $email = $this->email;

    //     // dd($this->getOriginal('email'));
    //     $this->email = $this->getOriginal('email');

    //     $this->save();

    //     $this->email = $email;
    // }
    //TEST tot hier


    //! de onderstaande functie zou moeten toe gevoegd worden. afhenkelijk van wat er gedaan wordt met het role systeem. voor creation van een nieuwe employee
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
