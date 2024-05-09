<?php

use App\Events\coach\NewChannelEvent;
use App\Http\Controllers\ChatWebsocketController;
use App\Http\Controllers\user\AuthenticatedSessionController;
use App\Http\Controllers\user\UserController;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\GoalPlanDisease;
use App\Models\Meal;
use App\Models\NormalUser;
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
    $trainees = GoalPlanDisease::first();
    dd($trainees->goals());
});


Route::post('/login', [AuthenticatedSessionController::class, 'loginApi']);
Route::post('/register', [AuthenticatedSessionController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('authenticate_websocket_mobile', [ChatWebsocketController::class, 'authenticateUser']);
    Route::get('/my-channels',[UserController::class,'myChannels']);
    Route::get('/is-premium',[UserController::class,'isPremium']);
    Route::get('/load-channel-old-message/{channelId}',[UserController::class,'loadChannelOldMessage']);
    Route::post('/send-new-message',[UserController::class,'sendNewMessage']);
});
