<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userProfile extends Model
{
    protected $table = 'user_profiles';
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'phone_number2',
        'profile_picture',
        'gender',
        'age',
    ];
}
