<?php

namespace App\Notifications\coach;

use App\Models\CoachTimeline;
use App\Models\NormalUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTraineeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private NormalUser $user, private CoachTimeline $timeline)
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


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "message" => "New Trainee Choose your plan.",
            "trainee" => (object)[
                "id" => $this->user->id,
                "fullname" => $this->user->fullname,
            ],
            "timeline" => (object)[
                "id" => $this->timeline->id,
                "name" => $this->timeline->name,
            ],
        ];
    }
}
