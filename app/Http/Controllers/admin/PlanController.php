<?php

namespace App\Http\Controllers\admin;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function allPlansView(): View
    {
        $plans = Plan::all();
        return view('pages.admin.plans.all-plans', compact('plans'));
    }
    public function editPlansView($id): View
    {
        $plan = Plan::find($id);
        return view('pages.admin.plans.edit-plan', compact('plan'));
    }
    public function editPlans(Request $request)
    {
        $plan = Plan::where('id', $request->id)->first();
        if (empty($plan) || !isset($plan))
            return back()->with('msg', 'plan not found.');
        // plan name must be unique .
        $plan_by_new_name = Plan::where('name', '=', $request->name)->whereNot('id', $request->id)->first();
        // if this var is exsists this mean there is already plan with this new name.
        if (!empty($plan_by_new_name) || isset($plan_by_new_name))
            return back()->with('msg', 'The new plan name already taken.');
        $plan->update(['name' => $request->name]);
        return back()->with('msg', 'updated.');
    }
    public function newPlansView(): View
    {
        return view('pages.admin.plans.new-plan');
    }
    public function newPlansStore(Request $request)
    {
        // plan name must be unique .
        $plan_by_new_name = Plan::where('name', '=', $request->name)->first();
        // if this var is exsists this mean there is already plan with this new name.
        if (!empty($plan_by_new_name) || isset($plan_by_new_name))
            return back()->with('msg', 'The new plan name already taken.');
        Plan::create(['name' => $request->name]);
        return back()->with('msg', 'Created.');
    }
    public function deletePlan(Request $request)
    {
        $plan_by_new_id = Plan::where('id', '=', $request->id)->first();
        if (empty($plan_by_new_id) || !isset($plan_by_new_id))
            return back()->with('msg', 'This plan not found.');
        $plan_by_new_id->delete();
        return back()->with('msg', 'Deleted.');
    }
}
