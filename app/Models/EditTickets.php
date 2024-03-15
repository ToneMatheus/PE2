<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditTickets extends Model
{
    protected $table = 'customerticket';

    protected $fillable = ['name', 'email', 'issue', 'description'];
}