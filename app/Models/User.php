<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'ID';

    //! de onderstaande functie zou moeten toe gevoegd worden. afhenkelijk van wat er gedaan wordt met het role systeem.
    // public function hasRole($role)
    // {
    //     return $this->role === $role;
    // }
}
