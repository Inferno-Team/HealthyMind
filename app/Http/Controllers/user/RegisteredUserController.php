<?php

namespace App\Http\Controllers\user;

use App\Events\admin\NewCoachEvent;
use App\Models\User;
use App\Models\Student;
use Illuminate\View\View;
use App\Models\Capability;
use Illuminate\Http\Request;
use App\Models\CapabilityType;
use Illuminate\Support\Carbon;
use App\Models\DeptSurveyScale;
use Illuminate\Validation\Rules;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\AllNotificationsEvent;
use App\Models\Admin;
use App\Models\Coach;
use App\Notifications\admin\NewCoachNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;

class RegisteredUserController extends Controller
{
    use LocalResponse;
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $coach = Coach::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->passowrd),
        ]);
        event(new Registered($coach));
        Auth::login($coach);
        $admin = Admin::first();
        $admin->notify(new NewCoachNotification($coach));
        event(new NewCoachEvent($coach, "admin-channel"));
        return redirect(RouteServiceProvider::COACH_HOME);
    }
}
