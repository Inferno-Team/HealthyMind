<?php

namespace App\Http\Helpers;

use App\Models\Admin;
use Illuminate\Notifications\Notification;

class CalculationHelper
{

    public static function calculateDifference($lastWeekCount, $beforeLastWeekCount)
    {
        if ($beforeLastWeekCount > 0) {
            if ($lastWeekCount == 0) {
                // Calculate the decrease as 100% of the initial count per unit decrease
                return -100 * $beforeLastWeekCount;
            } else {
                // Calculate the percentage change
                $diff = $lastWeekCount - $beforeLastWeekCount;
                return round(100 * $diff / $beforeLastWeekCount);
            }
        } else if ($lastWeekCount > 0) {
            // If there were no exercises in the previous week, calculate the percentage based on the current week count.
            return $lastWeekCount * 100;  // Reflecting the actual count as a percentage increase from 0.
        } else {
            return 0;
        }
    }
}
