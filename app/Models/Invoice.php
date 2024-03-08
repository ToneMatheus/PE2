<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'timestamp',
        'type',
        'price',
        'status',
        'due_date',
    ]; 

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
