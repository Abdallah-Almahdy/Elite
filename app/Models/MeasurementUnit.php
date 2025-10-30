<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{

    protected $table = 'measurement_units';

    protected $fillable = [
        'name',
        'base_measurement_unit_id',
        'conversion_factor',
        'is_base_unit',
    ];
    

}
