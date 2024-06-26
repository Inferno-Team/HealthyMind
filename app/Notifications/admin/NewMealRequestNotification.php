<?php

namespace App\Notifications\admin;

use Carbon\Carbon;
use App\Models\Meal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewMealRequestNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Meal $meal)
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
            "message" => "New Meal Request.",
            'name' => $this->meal->name,
            'coach' => $this->meal->coach,
            'type' => $this->meal->type,
            'qty_type' => $this->meal->qty_type,
            'qty' => $this->meal->qty,
            "created_at" => Carbon::now()->diffForHumans(),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->meal->name,
            'coach' => $this->meal->coach,
            'type' => $this->meal->type,
            'qty_type' => $this->meal->qty_type,
            'qty' => $this->meal->qty,
        ];
    }
}
