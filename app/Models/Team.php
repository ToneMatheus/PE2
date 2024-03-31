<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Team extends Model
{
    protected $fillable = [
        'id',
        'team_name',
    ];
    use HasFactory;

    public $timestamps = false;
    
    public function teamMember(): HasMany
    {
        return $this->hasMany(teamMember::class);
    }

    public function team_user_roles(): HasMany
    {
        return $this->hasMany(Team_User_Role::class);
    }
<<<<<<< HEAD
}
=======
    public function team_user_roles(): HasMany
    {
        return $this->hasMany(Team_User_Role::class);
    }
}
>>>>>>> fc66cd7f24f7876549dfeaf25946a4cdf276d6a9
