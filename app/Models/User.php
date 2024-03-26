<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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
    protected $appends = ['fullname'];
    protected $table = 'users';
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
    public function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name
        );
    }
    public function channels(): HasManyThrough
    {
        return $this->hasManyThrough(
            Channel::class,
            ChannelSubscription::class,
            'user_id',
            'id',
            'id',
            'channel_id'
        );
    }
    public function privateChannel()
    {
        if ($this->channels->isEmpty()) return null; // this user is not coach
        return $this->channels()->where('type', 'private')->first();
    }
    public function subscriptions(): HasMany
    {
        return $this->hasMany(ChannelSubscription::class, 'user_id');
    }
    public function messages(): HasManyThrough
    {
        return $this->hasManyThrough(
            MessageStatus::class,
            ChannelSubscription::class,
            'user_id',
            'subscription_id'
        );
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected static function booted()
    {
        static::addGlobalScope('type', function ($builder) {
            $allowedTypes = ['normal', 'coach', 'admin'];
            $className = class_basename(static::class);
            $type = strtolower(str_replace('User', '', $className));
            if (in_array($type, $allowedTypes)) {
                $builder->where('type', $type);
            }
        });
    }
}
