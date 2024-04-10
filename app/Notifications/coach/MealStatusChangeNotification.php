<?php

namespace App\Notifications\coach;

use App\Models\Meal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MealStatusChangeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Meal $meal)
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
            'name' => $this->meal->name,
            "status" => $this->meal->status,
            'coach' => $this->meal->coach,
            'type' => $this->meal->type,
            'qty_type' => $this->meal->qty_type,
            'qty' => $this->meal->qty,
        ];
    }
}
