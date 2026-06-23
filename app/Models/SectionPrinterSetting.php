<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionPrinterSetting extends Model
{
    protected $table = 'section_printer_settings';

    protected $fillable = [
        'sub_sections_id',
        'printer_name',
        'section_name',
    ];


}
