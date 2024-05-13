<?php

namespace App\Events\core;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private string $channelName,
        public string $channelType,
        public string $message,
        public int $message_id,
        public int $channel_id,
        public string $created_at,
        public string $created_at_diff,
        public User $sender
    ) {
        //
    }
    public function broadcastAs()
    {
        return "NewMessage";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channel = null;
        if ($this->channelType == 'private')
            $channel = new PrivateChannel($this->channelName);
        else if ($this->channelType == 'presence')
            $channel = new PresenceChannel($this->channelName);
        else
            $channel = new Channel($this->channelName);
        return [
            $channel,
        ];
    }
}
