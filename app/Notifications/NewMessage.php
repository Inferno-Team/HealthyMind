<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\SubscriptionMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public SubscriptionMessage $message,
        public Conversation $conversation,
        public User $sender,
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
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
