<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Meal;
use App\Models\User;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Exercise;
use App\Models\NormalUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\CalculationHelper;

class HomeController extends Controller
{

    public function home(): View
    {
        $users = NormalUser::get();
        $coachs = Coach::get();
        $meals = Meal::get();
        $exercises = Exercise::get();

        $oneWeekAgo = Carbon::now()->subWeek();
        $twoWeeksAgo = Carbon::now()->subWeeks(2);

        $trainees_count = $users->count();
        $coachs_count = $coachs->count();
        $meals_count = $meals->count();
        $exercises_count = $exercises->count();

        // Retrieve and count NormalUsers
        $lastWeekUsers = $users->where('created_at', '>=', $oneWeekAgo)->count();
        $beforeLastWeekUsers = $users->where('created_at', '>=', $twoWeeksAgo)
            ->where('created_at', '<', $oneWeekAgo)
            ->count();
        $differenceTraineesPercentage = CalculationHelper::calculateDifference($lastWeekUsers, $beforeLastWeekUsers);

        // Retrieve and count Coaches
        $lastWeekCoaches = $coachs->where('created_at', '>=', $oneWeekAgo)->count();
        $beforeLastWeekCoaches = $coachs->where('created_at', '>=', $twoWeeksAgo)
            ->where('created_at', '<', $oneWeekAgo)
            ->count();
        $differenceCoachesPercentage = CalculationHelper::calculateDifference($lastWeekCoaches, $beforeLastWeekCoaches);

        // Retrieve and count Meals
        $lastWeekMeals = $meals->where('created_at', '>=', $oneWeekAgo)->count();
        $beforeLastWeekMeals = $meals->where('created_at', '>=', $twoWeeksAgo)
            ->where('created_at', '<', $oneWeekAgo)
            ->count();
        $differenceMealsPercentage = CalculationHelper::calculateDifference($lastWeekMeals, $beforeLastWeekMeals);

        // Retrieve and count Exercises
        $lastWeekExercises = $exercises->where('created_at', '>=', $oneWeekAgo)->count();
        $beforeLastWeekExercises = $exercises->where('created_at', '>=', $twoWeeksAgo)
            ->where('created_at', '<', $oneWeekAgo)
            ->count();
        $differenceExercisesPercentage = CalculationHelper::calculateDifference($lastWeekExercises, $beforeLastWeekExercises);



        $timelineValues = [
            "Jan" => 0, "Feb" => 0, "Mar" => 0, "Apr" => 0,
            "May" => 0, "Jun" => 0, "Jul" => 0, "Aug" => 0,
            "Sep" => 0, "Oct" => 0, "Nov" => 0, "Dec" => 0
        ];

        $traineesTimelineValues = array_merge([], $timelineValues);
        $coachsTimelineValues = array_merge([], $timelineValues);
        $mealsTimelineValues = array_merge([], $timelineValues);
        $exercisesTimelineValues = array_merge([], $timelineValues);

        $timeline_meals = $meals->filter(function (Meal $meal) {
            $nowYear = Carbon::now()->year;
            $mealYear = Carbon::parse($meal->created_at)->year;
            return $nowYear == $mealYear;
        });
        $timeline_meals = $timeline_meals->groupBy(fn ($item) => $item->created_at->format('M'));
        foreach ($timeline_meals as $month => $value) {
            $mealsTimelineValues[$month] = $value->count();
        }

        $timeline_trainees = $users->filter(function (NormalUser $user) {
            $nowYear = Carbon::now()->year;
            $userYear = Carbon::parse($user->created_at)->year;
            return $nowYear == $userYear;
        });
        $timeline_trainees = $timeline_trainees->groupBy(fn ($item) => $item->created_at->format('M'));
        foreach ($timeline_trainees as $month => $value) {
            $traineesTimelineValues[$month] = $value->count();
        }

        $timeline_coaches = $coachs->filter(function (Coach $user) {
            $nowYear = Carbon::now()->year;
            $userYear = Carbon::parse($user->created_at)->year;
            return $nowYear == $userYear;
        });
        $timeline_coaches = $timeline_coaches->groupBy(fn ($item) => $item->created_at->format('M'));
        foreach ($timeline_trainees as $month => $value) {
            $coachsTimelineValues[$month] = $value->count();
        }

        $timeline_exercises = $exercises->filter(function (Exercise $exercise) {
            $nowYear = Carbon::now()->year;
            $exerciseYear = Carbon::parse($exercise->created_at)->year;
            return $nowYear == $exerciseYear;
        });
        $timeline_exercises = $timeline_exercises->groupBy(fn ($item) => $item->created_at->format('M'));
        foreach ($timeline_trainees as $month => $value) {
            $exercisesTimelineValues[$month] = $value->count();
        }

        return view('pages.admin.dashboard', compact(
            'trainees_count',
            'coachs_count',
            'meals_count',
            'exercises_count',

            'differenceTraineesPercentage',
            'differenceCoachesPercentage',
            'differenceMealsPercentage',
            'differenceExercisesPercentage',

            'traineesTimelineValues',
            'coachsTimelineValues',
            'mealsTimelineValues',
            'exercisesTimelineValues',
        ));
    }
    public function readNotification(Request $request)
    {
        $note_id = $request->input('id');
        DB::table('notifications')->where('id', $note_id)->update(['read_at' => now()]);
    }
}
