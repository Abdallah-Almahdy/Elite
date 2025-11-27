<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;



class ProductUnits extends Pivot
{
    protected $table = 'product_units';
    public $incrementing = true;
    protected $fillable = [
        'product_id',
        'unit_id',
        'conversion_factor',
        'price',
        'sallprice',
        'is_base',

    ];

    public function barcodes()
    {
        return $this->hasMany(Barcode::class, 'product_unit_id');
    }

    public function components()
    {
        return $this->hasMany(productUintComponent::class, 'product_unit_id');
    }



    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }


}
