<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kitchen extends Model
{
    use HasFactory;

    protected $table = 'kitchen';
    protected $fillable = ['name', 'active'];

    public function Printers()
    {
        return $this->belongsToMany(Printer::class, 'kitchen_printers');
    }

    public function subSections()
    {
        return $this->belongsToMany(SubSection::class, 'kitchen_subcategories', 'kitchen_id', 'category_id');
    }
}
