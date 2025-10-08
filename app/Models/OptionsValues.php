<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsValues extends Model
{
    use HasFactory;

    protected $table = 'options_values';

    protected $fillable = [
        'option_id',
        'name',
        'price',
    ];

    public function option()
    {
        return $this->belongsTo(Options::class);
    }
}
