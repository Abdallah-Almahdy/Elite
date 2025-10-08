<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Safe extends Model
{
    protected $fillable = [
        'name',
        'balance'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
