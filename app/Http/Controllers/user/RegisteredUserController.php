<?php

namespace App\Http\Controllers\user;

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
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Notifications\NewUserRegisteredNotification;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
            'capability' => ['required', 'exists:capability_types,id']
        ]);

        $user = User::create([
            'name' => $request->name,
            'type' => 'user',
            'phone' => $request->phone,
            'avatar' => $request->avatar,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            "capability_type_id" => $request->capability,
        ]);
        event(new Registered($user));
        Auth::login($user);
        $admin = User::find(1);
        // $admin->notify(new NewUserRegisteredNotification($user->name, $cap->en_title));
        $data = [];
        Carbon::setLocale('ar_JO');
        $counter = count($admin->unreadNotifications);
        $notifications = $admin->unreadNotifications;

        foreach ($notifications as $notification)
            $data[] = [
                ...$notification->data,
                "id" => $notification->id,
                "type" => $notification->type,
                "created_at" => Carbon::parse($notification->created_at)->diffForHumans()
            ];
        // event(new AllNotificationsEvent($data, $counter, $admin->id,));

        return redirect(RouteServiceProvider::HOME);
    }
}
