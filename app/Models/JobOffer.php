<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOffer extends Model
{
    protected $table = 'job_offers';
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image'
    ];

    public function job_applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    } 
}
