<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $appends = ['is_pro'];
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone',
        'password',
        'type',
        'status',
        'avatar',
    ];

    public function avatar(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attr) {
                if (empty($value) || empty($attr))
                    return null;
                return "/storage/$value";
            }
        );
    }
    public function isPro(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) =>
            isset($this->user_premium_request)
                && $this->user_premium_request->status == 'approved',

        );
    }
    public function user_premium_request(): HasOne
    {
        return $this->hasOne(UserPremiumRequest::class, 'user_id');
    }
    public function details(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
