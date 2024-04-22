<?php

namespace App\Http\Controllers\admin;

use \Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Exercise;
use App\Models\Meal;
use App\Models\NormalUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    //

    public function home(): View
    {
        $users = NormalUser::get();
        $coachs = Coach::get();
        $meals = Meal::get();
        $exercises = Exercise::get();

        $users_count = $users->count();
        $coachs_count = $coachs->count();
        $meals_count = $meals->count();
        $exercises_count = $exercises->count();

        $new_users_count = $users->filter(fn ($item) => $item->created_at->isAfter(Carbon::now()->subWeek()))->count();
        $new_coachs_count = $coachs->filter(fn ($item) => $item->created_at->isAfter(Carbon::yesterday()))->count();
        $new_meals_count = $meals->filter(fn ($item) => $item->created_at->isAfter(Carbon::now()->subWeek()))->count();
        $new_exercises_count = $exercises->filter(fn ($item) => $item->created_at->isAfter(Carbon::yesterday()))->count();

        $users_old_without_last_week = ($users_count - $new_users_count);
        $coachs_old_without_yesterday = ($coachs_count - $new_coachs_count);
        $meals_old_without_last_month = ($meals_count - $new_meals_count);

        $users_percentage = round($new_users_count * 100 / ($users_old_without_last_week > 0 ? $users_old_without_last_week : 1));
        $coachs_percentage = round($new_coachs_count * 100 / ($coachs_old_without_yesterday > 0 ? $coachs_old_without_yesterday : 1));
        $meals_percentage = round($new_meals_count * 100 / ($meals_old_without_last_month > 0 ? $meals_old_without_last_month : 1));

        return view('pages.dashboard', compact(
            'users_count',
            'coachs_count',
            'coachs_percentage',
            'users_percentage',
            'meals_count',
            'exercises_count',
            'new_meals_count',
            'new_exercises_count',
        ));
    }
    public function readNotification(Request $request)
    {
        $note_id = $request->input('id');
        DB::table('notifications')->where('id', $note_id)->update(['read_at' => now()]);
    }
}
