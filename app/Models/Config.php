<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $fillable = [
        'exact_blocked_version',
        'min_supported_version',
        'maintenance_mode',
        'maintenance_message',
        'blocked_versions',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'blocked_versions' => 'array',
    ];
}


