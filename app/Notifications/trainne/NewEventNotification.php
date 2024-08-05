<?php

namespace App\Notifications\trainne;

use App\Models\Meal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TimelineItem;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\User;


class NewEventNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private TimelineItem $item, private string $trainee_username)
    {
        //
    }

    public function broadcastOn()
    {

        return "presence-" . $this->trainee_username;
    }
    public function broadcastAs()
    {
        return "NewEventNotification";
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
            "title" => "New Timeline Event",
            "message" => "New Item has been added to timeline that you are subscribed to",
            "item_name" => $this->item->item->name,
            "created_at" => $this->item->created_at,
            "is_meal" => $this->item->item instanceof Meal,
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
            "title" => "New Timeline Event",
            "message" => "New Item has been added to timeline that you are subscribed to",
            "item_name" => $this->item->item->name,
            "created_at" => $this->item->created_at,
            "is_meal" => $this->item->item instanceof Meal,
        ];
    }
}
