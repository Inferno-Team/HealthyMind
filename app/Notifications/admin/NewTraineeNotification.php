<?php

namespace App\Notifications\admin;

use Carbon\Carbon;
use App\Models\NormalUser;
use App\Models\CoachTimeline;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewTraineeNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private NormalUser $user, private CoachTimeline $timeline)
    {
        //
    }
    public function broadcastAs()
    {
        return "NewTraineeNotification";
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
            "message" => "New Trainee.",
            "trainee" => (object)[
                "id" => $this->user->id,
                "fullname" => $this->user->fullname,
            ],
            "timeline" => (object)[
                "id" => $this->timeline->id,
                "name" => $this->timeline->name,
            ],
            "created_at" => Carbon::now()->diffForHumans(),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            "message" => "New Trainee.",
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
