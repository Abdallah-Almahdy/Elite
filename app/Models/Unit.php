<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_code',
        'unit_code',
        'is_base_unit',
        'conversion_factor',
        'base_unit_id',
        'type',
        'quantity',
        'active'
    ];

    protected $casts = [
        'is_base_unit' => 'boolean',
        'active' => 'boolean',
        'conversion_factor' => 'decimal:4'
    ];

    // Relationship with base unit
    public function baseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    // Relationship with derived units
    public function derivedUnits(): HasMany
    {
        return $this->hasMany(Unit::class, 'base_unit_id');
    }

    // Convert value from this unit to base unit
    public function convertToBase($value)
    {
        return $value / $this->conversion_factor;
    }

    // Convert value from base unit to this unit
    public function convertFromBase($value)
    {
        return $value * $this->conversion_factor;
    }

    // Relationship with ingredients units
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    // Scope active units
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_units')
                    ->withTimestamps();
    }
}
