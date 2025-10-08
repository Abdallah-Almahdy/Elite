<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'type',
        'photo',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_notifications')
                    ->withPivot('is_read')
                    ->withTimestamps();
    }

}
