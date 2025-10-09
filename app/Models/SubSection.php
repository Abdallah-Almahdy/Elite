<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubSection extends Model
{
    use HasFactory;


    protected $table = 'sub_sections';
    protected $fillable = [
        'name',
        'description',
        'photo',
        'active',
        'main_section_id',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


    public function main_section(): BelongsTo
    {
        return $this->belongsTo(Section::class,  'main_section_id', 'id');
    }


}
