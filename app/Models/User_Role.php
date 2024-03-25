<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User_Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_id',
        'is_active'
    ];
}
