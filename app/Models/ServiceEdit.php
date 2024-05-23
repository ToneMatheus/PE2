<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceEdit extends Model
{
    protected $table = 'tickets';

    protected $fillable = ['name', 'email', 'issue', 'description', 'resolution'];
}