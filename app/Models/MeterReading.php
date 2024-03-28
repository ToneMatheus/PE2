<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Http\Controllers\MeterReadingController;

class MeterReading extends Model
{
    use HasFactory;
    public $fillable = ['reading', 'date'];
}
