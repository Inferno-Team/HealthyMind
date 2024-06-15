<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\SubscriptionMessage;
use App\Models\User;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PresenceChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public SubscriptionMessage $message,
        public Conversation $conversation,
        public User $sender,
        private User $notifiable,
    ) {
        //
    }
    public function broadcastOn()
    {

        return "presence-" . $this->notifiable->username;
    }
    public function broadcastAs()
    {
        return "NewMessage";
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            "conversation" => $this->conversation->withoutRelations(),
            "sender" => $this->sender->withoutRelations(),
            "message" => $this->message->withoutRelations(),
        ]);
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "conversation" => $this->conversation->withoutRelations(),
            "sender" => $this->sender->withoutRelations(),
            "message" => $this->message->withoutRelations(),
        ];
    }
}
