<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';
    protected $fillable = [
        'name',
        'description',
        'photo',
        'active'
    ];



    public function sub_section(): HasMany
    {
        return $this->hasMany(SubSection::class, 'main_section_id');
    }

}
