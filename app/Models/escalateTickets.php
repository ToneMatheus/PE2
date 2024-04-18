<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class escalateTickets extends Model
{
    protected $table = 'customerticket';

    protected $fillable = ['name', 'email', 'issue', 'description'];
}