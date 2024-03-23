<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
    protected $appends = ['is_pro', 'fullname'];
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
    public function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name
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
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public static function boot()
    {
        parent::boot();
        static::created(function (User $user) {
            // check if this user is coach create channel and create subscription for this user
            // channel type is private [only the system and this user can subscribe to it.]
            if ($user->type == 'coach') {
                $channel = Channel::create([
                    'name' => 'Coach.' . $user->id,
                    'type' => 'private',
                ]);
                ChannelSubscription::create([
                    'channel_id' => $channel->id,
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
