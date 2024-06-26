<?php

namespace App\Notifications\coach;

use Carbon\Carbon;
use App\Models\Exercise;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ExerciseStatusChangeNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Exercise $exercise)
    {
        //
    }
    public function broadcastAs()
    {
        return "ExerciseNotification";
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

        return "private-Coach." . $this->exercise->coach_id;
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            "message" => "Admin Changed Exercise Status",
            "exercise" => (object)[
                "id" => $this->exercise->id,
                "name" => $this->exercise->name,
            ],
            "status" => $this->exercise->status,
            "created_at" => Carbon::now()->diffForHumans(),
        ]);
    }
    public function toArray(object $notifiable): array
    {
        return [
            "message" => "Admin Changed Exercise Status",
            "exercise" => (object)[
                "id" => $this->exercise->id,
                "name" => $this->exercise->name,
            ],
            "status" => $this->exercise->status,
        ];
    }
}
