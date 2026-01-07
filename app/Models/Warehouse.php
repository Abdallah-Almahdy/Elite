<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean'
    ];

    // Relationship with addresses (polymorphic)
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    // Relationship with phones (polymorphic)
    public function phones(): MorphMany
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    // Relationship with products (if you add this later)
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Get primary address
    public function primaryAddress()
    {
        return $this->addresses()->where('is_primary', true)->first();
    }

    // Get primary phone
    public function primaryPhone()
    {
        return $this->phones()->where('is_primary', true)->first();
    }
}
