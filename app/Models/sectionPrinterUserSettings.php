<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sectionPrinterUserSettings extends Model
{
    protected $table = 'section_printer_user_settings';

    protected $fillable = [
        'sub_sections_id',
        'section_name',
        'printer_name'
        ];


}
