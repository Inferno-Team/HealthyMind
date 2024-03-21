<?php

namespace App\Http\Controllers\coach;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoachController extends Controller
{
    public function home_view(): View
    {
        // get all this user channels
        $channels = Auth::user()->channels->map(fn($item)=> (object)[$item->type=> $item->name]);
        info($channels);
        return view('pages.coach.dashboard',compact('channels'));
    }
}
