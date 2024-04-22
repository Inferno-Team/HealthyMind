<?php

namespace App\Notifications\admin;

use App\Models\Exercise;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewExerciseRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Exercise $exercise)
    {
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
