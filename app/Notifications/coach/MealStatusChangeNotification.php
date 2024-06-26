<?php

namespace App\Notifications\coach;

use App\Models\Meal;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MealStatusChangeNotification extends Notification implements ShouldQueue, ShouldBroadcast
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
        return "MealNotification";
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

        return "private-Coach." . $this->meal->coach_id;
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            "message" => "Admin Changed Meal Status",
            "name" => $this->meal->name,
            "status" => $this->meal->status,
            "created_at" => Carbon::now()->diffForHumans(),
        ]);
    }
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->meal->name,
            "status" => $this->meal->status,
        ];
    }
}
