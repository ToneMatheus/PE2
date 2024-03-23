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
    
    public function teamMember(): HasMany
    {
        return $this->hasMany(teamMember::class);
    }
}