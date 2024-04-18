<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team_User_Role extends Model
{
    use HasFactory;
    protected $table = 'team_user_roles';

    protected $fillable = [
        'team_id',
        'user_role_id'
    ];

    use HasFactory;

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user_role(): BelongsTo
    {
        return $this->belongsTo(User_Role::class);
    }
}
