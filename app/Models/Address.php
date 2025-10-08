<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'type',
        'line1',
        'line2',
        'city',
        'state',
        'postal_code',
        'country',
        'is_primary'
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}
