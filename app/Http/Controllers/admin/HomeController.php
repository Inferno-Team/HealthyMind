<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    //

    public function home(): View
    {
        return view('pages.dashboard');
    }
}
