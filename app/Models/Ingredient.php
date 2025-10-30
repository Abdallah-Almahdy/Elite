<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'measurement_unit_id',
        'quantity_in_stock',
        'derived_quantity',
        'is_active'
    ];

    protected $casts = [
        'quantity_in_stock' => 'decimal:4',
        'derived_quantity'   => 'decimal:4',
        'is_active'          => 'boolean',
    ];


    public function MeasurementUnit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function recipes()
{
    return $this->belongsToMany(Recipe::class, 'recipe_ingredients')
                ->withPivot('quantity_needed')
                ->withTimestamps();
}




}
