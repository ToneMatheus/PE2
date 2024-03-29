<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class LeaderRelation extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'leader_id',
        'employee_id',
        'relation',
        ];

        public function leader(): BelongsTo
        {
        return $this->belongsTo(User::class);
        }
        public function employee(): BelongsTo
        {
        return $this->belongsTo(User::class);
        }

    use HasFactory;
}
