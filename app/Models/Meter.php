<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    use HasFactory;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = ['EAN','type', 'installationDate', 'status'];

    public $table = "meter";

    public function scopeSearch($query, $value){
        $query->where('ID', 'like', "%{$value}%")->orWhere('EAN', 'like', "%{$value}%")->orWhere('type', 'like', "%{$value}%")->orWhere('installationDate', 'like', "%{$value}%")->orWhere('status', 'like', "%{$value}%");
    }
}
