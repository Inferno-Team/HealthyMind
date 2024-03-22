<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();
        info(Auth::user()->type);
        if (Auth::user()->type == 'coach')
            return redirect(RouteServiceProvider::COACH_HOME);
        return redirect()->intended(RouteServiceProvider::HOME);
    }


    public function loginApi(LoginRequest $request)
    {
        $user = User::where('email', "like", $request->email)->first();
        if (empty($user))
            return $this->returnError("user not found.", 203);

        if (!Hash::check($request->password, $user->password))
            return $this->returnError("password not matching", 203);

        $token = $user->createToken('authToken')->plainTextToken;

        $request->session()->regenerate();
        return $this->returnData('data', [
            "token" => $token,
            "user" => $user
        ]);
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
    public function findNextRoute(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->type == 'coach')
                return redirect(RouteServiceProvider::COACH_HOME);
            else if (Auth::user()->type == 'admin')
                return redirect(RouteServiceProvider::HOME);
            else if (Auth::user()->type == 'normal')
                return abort(403);
            else return abort(404);
        }
        return redirect('login');
    }
}
