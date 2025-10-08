<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'contactable_id',
        'contactable_type',
        'name',
        'email',
        'phone',
        'position',
        'is_primary'
    ];

    public function contactable()
    {
        return $this->morphTo();
    }
}
