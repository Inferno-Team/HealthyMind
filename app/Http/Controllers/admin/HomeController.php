<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Meal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use \Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //

    public function home(): View
    {
        $users = User::where('type', 'normal')->get();
        $coatchs = User::where('type', 'coatch')->get();
        $meals = Meal::get();
        $exercises = Exercise::get();

        $users_count = $users->count();
        $coatchs_count = $coatchs->count();
        $meals_count = $meals->count();
        $exercises_count = $exercises->count();

        $new_users_count = $users->filter(fn ($item) => $item->created_at->isAfter(Carbon::now()->subWeek()))->count();
        $new_coatchs_count = $coatchs->filter(fn ($item) => $item->created_at->isAfter(Carbon::yesterday()))->count();
        $new_meals_count = $meals->filter(fn ($item) => $item->created_at->isAfter(Carbon::now()->subWeek()))->count();
        $new_exercises_count = $exercises->filter(fn ($item) => $item->created_at->isAfter(Carbon::yesterday()))->count();
        
        $users_percentage = round($new_users_count * 100 / $users_count);
        $coatchs_percentage = round($new_coatchs_count * 100 / ($coatchs_count == 0 ? 1 : $coatchs_count));


        return view('pages.dashboard', compact(
            'users_count',
            'coatchs_count',
            'coatchs_percentage',
            'users_percentage',
            'meals_count',
            'exercises_count',
            'new_meals_count',
            'new_exercises_count',
        ));
    }
}
