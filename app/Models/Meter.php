<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use app\Models\Connection;

class Meter extends Model
{
    use HasFactory;
    
    public $table = "meters";

    const CREATED_AT = null;
    const UPDATED_AT = null;

     protected $fillable = [
        'id',
        'EAN',
        'type',
        'installation_date',
        'status',
        'is_smart',
        'expecting_reading',
        'has_validation_error'
    ];

     public function scopeSearch($query, $value){
         $query->where('ID', 'like', "%{$value}%")->orWhere('EAN', 'like', "%{$value}%")->orWhere('type', 'like', "%{$value}%")->orWhere('installationDate', 'like', "%{$value}%")->orWhere('status', 'like', "%{$value}%");
     }

     public function contract_products(): HasMany
     {
         return $this->hasMany(Contract_product::class);
     }

     public function estimations(): HasMany
     {
         return $this->hasMany(Estimation::class);
     }

     public function index_values(): HasMany
     {
         return $this->hasMany(Index_Value::class);
     }

     public function meter_reader_schedules(): HasMany
     {
         return $this->hasMany(Meter_Reader_Schedule::class);
     }

     public function meter_addresses(): HasMany
     {
         return $this->hasMany(Meter_Addresses::class);
     }

     public function meters(): HasMany
     {
         return $this->hasMany(Meter::class);
     }

     public function gaselectricity(): HasMany
     {
        return $this->hasMany(MeterReading::class);
     }


}
