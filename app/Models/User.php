<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\OrderTracking;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasFactory, Notifiable, HasApiTokens;
    use HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function ratings(): HasMany
    {
        return $this->HasMany(Rating::class);
    }
    public function promoCodes(): HasMany
    {
        return $this->HasMany(PromoCode::class);
    }
    public function orderTracking(): HasMany
    {
        return $this->HasMany(OrderTracking::class);
    }
    public function orders(): HasMany
    {
        return $this->HasMany(Order::class);
    }

    public function customerInfo(): HasOne
    {
        return $this->hasOne(CustomerInfo::class, 'user_id');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'users_notifications')
                    ->withPivot('is_read')
                    ->withTimestamps();
    }


        public function contactUs(): HasMany
    {
        return $this->HasMany(ContactUs::class);
    }
}
