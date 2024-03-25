<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team_Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'is_manager'
    ];
}
