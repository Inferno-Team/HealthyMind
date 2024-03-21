<?php

use App\Models\Meal;
use App\Models\User;
use App\Models\UserPremiumRequest;
use App\Notifications\NewCoachNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    // $user = User::find(4);
    // dd($user->is_pro);
    Meal::create([
        ''
    ]);
    // UserPremiumRequest::create([
    //     'user_id' => 4,
    //     'payment_process_code' => '800000005516202',
    // ]);
    // $user->notify(new NewCoachNotification('title', 'subtitle', 'new-meal'));
});
