<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userAddress extends Model
{
    protected $table = 'user_addresses';
    protected $fillable = [
        'user_id',
        'address_governorate',
        'delivery_place_id',
        'address_city',
        'address_street',
        'address_building',
        'address_floor',
        'address_apartment',
        'is_default',

    ];


    protected $casts = [
        'is_default' => 'boolean',
        'delivery_place_id' => 'int'
    ];
}
