<?php

use App\Events\coach\NewChannelEvent;
use App\Http\Controllers\ChatWebsocketController;
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
    $user = User::find(15);
    $channel = $user->privateChannel();
    event(new NewChannelEvent('Trainee.17',$channel->name,$user->id));
});
