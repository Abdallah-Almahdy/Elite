<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Printer extends Model
{
    use HasFactory;

    protected $table = 'printers';
    protected $fillable = [
        'name',
        'active',
        'model',

    ];

    public function Kitchens()
    {
        return $this->belongsToMany(kitchen::class, 'kitchen_printers');
    }

    public function PrintJob(): HasMany
    {
        return $this->HasMany(PrintJob::class);
    }
}
