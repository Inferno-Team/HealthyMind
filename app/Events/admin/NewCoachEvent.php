<?php

namespace App\Events\admin;

use App\Models\Coach;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCoachEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(public Coach $coach, private string $channel_name)
    {
    }
    public function boradcastAs()
    {
        return "NewCoach";
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel($this->channel_name),
        ];
    }
}
