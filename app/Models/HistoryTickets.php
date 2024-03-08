<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryTickets extends Model
{
    protected $table = 'customerticket';

    protected $fillable = ['name', 'email', 'issue', 'description'];
}

