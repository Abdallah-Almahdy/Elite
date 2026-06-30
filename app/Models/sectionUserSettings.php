<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sectionUserSettings extends Model
{
    protected $table = "section_user_settings";

    protected $fillable = [
        'allowdSections',
        'seenSections',
        "user_id"

    ];

    public function sectionPrinter()
    {
        return $this->hasMany(sectionPrinterUserSettings::class,'');
    }
    protected $casts = [
        'allowdSections' => 'array',
        'seenSections' => 'array',
    ];
}

