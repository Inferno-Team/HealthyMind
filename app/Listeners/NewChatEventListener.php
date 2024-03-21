<?php

namespace App\Listeners;

use App\Events\coach\NewChannelEvent;
use App\Models\Channel;
use App\Models\ChannelSubscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewChatEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewChannelEvent $event): void
    {
        // this mean this coach is not subscribed to this new channel.
        ChannelSubscription::create([
            'channel_id' => Channel::where('name', $event->newChannelName)->first()->id,
            'user_id' => $event->coachId,
        ]);
    }
}
