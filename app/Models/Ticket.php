<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Ticket extends Model
{

    use Notifiable;

    public const VALIDATION_RULE_NAME = ['required'];

    public const VALIDATION_RULE_ISSUE = ['required'];

    public const VALIDATION_RULE_DESCRIPTION = ['required'];

    public const VALIDATION_RULE_EMAIL = ['required'];

    
    public const VALIDATION_RULES = [
        'name' => self::VALIDATION_RULE_NAME,
        'email' => self::VALIDATION_RULE_EMAIL,
        'issue' => self::VALIDATION_RULE_ISSUE,
        'description' => self::VALIDATION_RULE_DESCRIPTION
    ];

    protected $primaryKey = 'id';

    protected $table = 'tickets';

    protected $fillable = [
        'id',
        'role',
        'name',
        'email',
        'issue',
        'description',
        'active',
        'user_id',
        'status', 'resolution'
    ];

    public static function validate(array $input): bool
    {
        $rules = static::VALIDATION_RULES;

        return \Illuminate\Support\Facades\Validator::make($input, $rules)->passes();
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function employee_tickets(): HasMany
    {
        return $this->hasMany(Employee_Ticket::class);
    }
}
