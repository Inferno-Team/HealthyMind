<?php

namespace App\Notifications\admin;

use Carbon\Carbon;
use App\Models\Coach;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewCoachNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Coach $coach)
    {
        //
    }

    public function broadcastAs()
    {
        return "NewMealRequestNotification";
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
    public function broadcastOn()
    {

        return "private-admin-channel";
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            "coach_name" => $this->coach->fullname,
            "created_at" => $this->coach->created_at->diffForHumans(),
            "msg" => "New Coach Request",
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
            "coach_name" => $this->coach->fullname,
            "created_at" => $this->coach->created_at->diffForHumans(),
            "msg" => "New Coach Request",
        ];
    }
}
