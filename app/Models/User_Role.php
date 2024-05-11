<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User_Role extends Model
{

    use HasFactory;
    protected $table = 'user_roles';

    protected $fillable = [
        'user_id',
        'role_id',
        'is_active'
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function team_user_roles(): HasMany
    {
        return $this->hasMany(Team_User_Role::class);
    }
}
