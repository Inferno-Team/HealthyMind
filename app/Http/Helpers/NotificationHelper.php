<?php

namespace App\Http\Helpers;

use App\Models\Admin;
use Illuminate\Notifications\Notification;

class NotificationHelper
{

    public static function notifyAdmins(Notification $notification)
    {
        $admins = Admin::all();
        foreach ($admins as $admin) {
            $admin->notify($notification);
        }
    }
}
