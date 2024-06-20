<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coach extends User
{
    public function timelines(): HasMany
    {
        return $this->hasMany(CoachTimeline::class, 'coach_id');
    }
    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class, 'coach_id');
    }
    public function timeline_trainees()
    {
        return $this->hasManyThrough(TraineeTimeline::class, CoachTimeline::class, 'coach_id', 'timeline_id');
    }
    public function privateChannel()
    {
        if ($this->channels->isEmpty()) return null; // this user is not coach
        return $this->channels()->where('type', 'private')->first();
    }
    public function conversations(): Collection
    {
        // get all conversation based on my private channel.
        $privateChannel = $this->privateChannel();
        return Conversation::where('channel_id', $privateChannel->id)->get();
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
