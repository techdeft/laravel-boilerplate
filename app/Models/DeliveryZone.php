<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'area',
        'country',
        'delivery_fee',
        'local_park_fee',
        'local_park_instructions',
        'special_surcharge',
        'free_delivery_threshold',
        'is_active',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'local_park_fee' => 'decimal:2',
        'special_surcharge' => 'decimal:2',
        'free_delivery_threshold' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
