<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $fillable = [
        'min_supported_version',
        'exact_blocked_version',
        'maintenance_mode',
        'maintenance_message',
        'color',
    ];

    public static function instance(): self
    {
        return static::first() ?? static::create();
    }


}


