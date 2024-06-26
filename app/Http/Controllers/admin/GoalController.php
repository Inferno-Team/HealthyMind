<?php

namespace App\Http\Controllers\admin;

use App\Models\Goal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class GoalController extends Controller
{
    public function allGoalsView(): View
    {
        $goals = Goal::all();
        return view('pages.admin.goals.all-goals', compact('goals'));
    }
    public function editGoalsView($id): View
    {
        $goal = Goal::find($id);
        return view('pages.admin.goals.edit-goal', compact('goal'));
    }
    public function editGoals(Request $request)
    {
        $goal = Goal::where('id', $request->id)->first();
        if (empty($goal) || !isset($goal))
            return back()->with('msg', 'goal not found.');
        // goal name must be unique .
        $goal_by_new_name = Goal::where('name', '=', $request->name)->whereNot('id', $request->id)->first();
        // if this var is exsists this mean there is already goal with this new name.
        if (!empty($goal_by_new_name) || isset($goal_by_new_name))
            return back()->with('msg', 'The new goal name already taken.');
        $goal->update(['name' => $request->name]);
        return back()->with('msg', 'updated.');
    }
    public function newGoalsView(): View
    {
        return view('pages.admin.goals.new-goal');
    }
    public function newGoalsStore(Request $request)
    {
        // goal name must be unique .
        $goal_by_new_name = Goal::where('name', '=', $request->name)->first();
        // if this var is exsists this mean there is already goal with this new name.
        if (!empty($goal_by_new_name) || isset($goal_by_new_name))
            return back()->with('msg', 'The new goal name already taken.');
        Goal::create(['name' => $request->name]);
        return back()->with('msg', 'Created.');
    }
    public function deleteGoal(Request $request)
    {
        $goal_by_new_id = Goal::where('id', '=', $request->id)->first();
        if (empty($goal_by_new_id) || !isset($goal_by_new_id))
            return back()->with('msg', 'This goal not found.');
        $goal_by_new_id->delete();
        return back()->with('msg', 'Deleted.');
    }
}
