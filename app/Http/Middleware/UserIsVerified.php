<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('landing_page')->with('msg', "You don't have access to this route, login please.");
        }
        $user = Auth::user();
        if ($user->isDeclined()) {
            return redirect()->route('errors.declined');
        }
        if ($user->notVerified()) {
            return redirect()->route('errors.not-verified');
        }
        return $next($request);
    }
}
