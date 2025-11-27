<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    protected $fillable = [
        'code',
        'product_unit_id'
    ];

    public function productUnit()
    {
        return $this->belongsTo(ProductUnits::class, 'product_unit_id');
    }
}
