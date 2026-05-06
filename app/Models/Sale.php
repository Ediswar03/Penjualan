<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'sales',
        'activity',
        'activity_name',
        'rainfall_mm',
        'rain_level',
    ];

    protected $casts = [
        'date' => 'date',
        'activity' => 'boolean',
        'sales' => 'integer',
        'rainfall_mm' => 'integer',
    ];
}
