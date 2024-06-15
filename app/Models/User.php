<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
                $prefix = request()->getSchemeAndHttpHost();
                return "$prefix/storage/$value";
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

    public function conversationMembership(): HasMany
    {
        return $this->hasMany(ConversationMember::class, "user_id");
    }
    public function subscriptions(): HasMany
    {
        return $this->hasMany(ChannelSubscription::class, 'user_id');
    }
    // public function messages(): HasManyThrough
    // {
    //     return $this->hasManyThrough(
    //         MessageStatus::class,
    //         ChannelSubscription::class,
    //         'user_id',
    //         'subscription_id'
    //     );
    // }
    public function message(): HasManyThrough
    {
        return $this->hasManyThrough(
            SubscriptionMessage::class,
            ConversationMember::class,
            null,
            "member_id",
        );
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public static function boot()
    {
        parent::boot();
        static::created(function (self $item) {
            ChannelSubscription::create([
                'channel_id' => 1, // this is the id of all-chat channel
                'user_id' => $item->id,
            ]);
            ConversationMember::create([
                "conversation_id" => 1,
                "user_id" => $item->id,
            ]);
        });
    }

    public function format()
    {
        return (object)[
            "id" => $this->id,
            "username" => $this->username,
            "avatar" => $this->avatar,
            "email" => $this->email,
            "channels" => $this->channels->map->formatForUser($this->id),
            "password" => $this->password,
        ];
    }
    protected static function booted()
    {
        parent::booted();
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
