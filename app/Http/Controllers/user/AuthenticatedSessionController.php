<?php

namespace App\Http\Controllers\user;


use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\NormalUser;
use App\Models\User;
use App\Models\UserDetail;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        if (Auth::user()->type == 'coach')
            return redirect(RouteServiceProvider::COACH_HOME);
        if (Auth::user()->type == 'normal') {
            Auth::logout();
            return redirect("/")->with('msg', 'use mobile application to login as trainee');
        }
        return redirect()->intended(RouteServiceProvider::HOME);
    }


    public function loginApi(LoginRequest $request)
    {
        $user = NormalUser::where('email', "like", $request->email)->with('channels')->first();
        if (empty($user))
            return $this->returnError("user not found.", 404);
        if (!Hash::check($request->password, $user->password))
            return $this->returnError("password not matching", 401);

        $token = $user->createToken('login-via-api')->plainTextToken;
        $user = $user->format();
        unset($user->password);
        return $this->returnData('data', [
            "token" => $token,
            "user" => $user,
        ]);
    }
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        $user = NormalUser::create($request->userData());
        $userDetail = UserDetail::create($request->userDetails($user->id));
        $token = $user->createToken('register-via-api')->plainTextToken;
        DB::commit();
        $user = $user->format();
        unset($user->password);
        return $this->returnData('data', [
            "token" => $token,
            "user" => $user,
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->returnMessage("Logged out successfully.");
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
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
