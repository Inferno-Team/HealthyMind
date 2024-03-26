<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Coach extends User
{
    public function timelines(): HasMany
    {
        return $this->hasMany(CoachTimeline::class, 'coach_id');
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function (User $user) {
            $user->forceFill(['type' => 'coach']);
        });
        static::created(function (Coach $user) {
            // create channel and create subscription for this coach
            // channel type is private [only the system and this user can subscribe to it.]
            $channel = Channel::create([
                'name' => 'Coach.' . $user->id,
                'type' => 'private',
            ]);
            ChannelSubscription::create([
                'channel_id' => $channel->id,
                'user_id' => $user->id,
            ]);
        });
    }
}
