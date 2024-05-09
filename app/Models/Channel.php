<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Channel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
    ];
    public function subscriptions(): HasMany
    {
        return $this->hasMany(ChannelSubscription::class, 'channel_id');
    }
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            ChannelSubscription::class,
            'channel_id',
            'id',
            'id',
            'user_id'
        );
    }
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'channel_id');
    }
    public function messages()
    {
        return $this->hasManyThrough(
            SubscriptionMessage::class,
            ChannelSubscription::class,
            'channel_id', // Foreign key on the subscriptions table
            'subscription_id', // Foreign key on the subscription_messages table
            'id', // Local key on the channels table
            'id' // Local key on the subscriptions table
        );
    }
}
