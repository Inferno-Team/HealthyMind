<?php

namespace App\Notifications\admin;

use Carbon\Carbon;
use App\Models\Exercise;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewExerciseRequestNotification extends Notification implements ShouldQueue, ShouldBroadcast
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
            "message" => "New Exercise Request.",
            'name' => $this->exercise->name,
            'coach' => $this->exercise->coach,
            'type' => $this->exercise->type,
            'equipment' => $this->exercise->equipment,
            'duration' => $this->exercise->duration,
            'muscle' => $this->exercise->muscle,
            'media' => $this->exercise->media,
            "created_at" => Carbon::now()->diffForHumans(),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->exercise->name,
            'coach' => $this->exercise->coach,
            'type' => $this->exercise->type,
            'equipment' => $this->exercise->equipment,
            'duration' => $this->exercise->duration,
            'muscle' => $this->exercise->muscle,
            'media' => $this->exercise->media,
        ];
    }
}
