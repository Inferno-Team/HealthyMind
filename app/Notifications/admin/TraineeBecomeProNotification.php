<?php

namespace App\Notifications\admin;

use Carbon\Carbon;
use App\Models\NormalUser;
use App\Models\CoachTimeline;
use App\Models\UserPremiumRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TraineeBecomeProNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private NormalUser $user, private UserPremiumRequest $userPremiumRequest)
    {
        //
    }

    public function broadcastAs()
    {
        return "TraineeBecomeProNotification";
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
            "message" => "New Trainee Choose your plan.",
            "trainee" => (object)[
                "id" => $this->user->id,
                "fullname" => $this->user->fullname,
            ],
            "request" => [
                "code" => $this->userPremiumRequest->payment_process_code,
            ],
            "created_at" => Carbon::now()->diffForHumans(),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            "message" => "Trainee Become Pro.",
            "trainee" => (object)[
                "id" => $this->user->id,
                "fullname" => $this->user->fullname,
            ],
            "request" => [
                "code" => $this->userPremiumRequest->payment_process_code,
            ],
        ];
    }
}
