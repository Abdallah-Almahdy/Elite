<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUS extends Model
{
    use HasFactory;
    protected $table = 'about_us';
    protected $fillable=[
        'logo',
        'company_name',
        'short_description',
        'full_description',
        'facebook',
        'whatsapp',
        'instagram',
        'phone',
        'email',
        'address',
        'work_from',
        'work_to',
        'experience_years',
        'happy_clients',
        'successful_projects',
        'location'];

        public function images()
    {
        return $this->hasMany(AboutUsImage::class, 'about_us_id');
    }
    public function getWorkFromAttribute($value)
{
    return \Carbon\Carbon::parse($value)->format('h:i A');


}
        public function getWorkToAttribute($value)
{
    return \Carbon\Carbon::parse($value)->format('h:i A');
}
}

