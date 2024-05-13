<?php

use App\Events\core\NewMessageEvent;
use App\Events\TestEvent;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\ChatWebsocketController;
use App\Http\Controllers\coach\CoachController;
use App\Http\Controllers\user\AuthenticatedSessionController;
use App\Http\Controllers\user\RegisteredUserController;
use App\Models\ChannelSubscription;
use App\Models\MessageStatus;
use App\Models\SubscriptionMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/t', function () {
    $channel_id = 3;
    $messageText = "hello from test";
    //we have channel id and user id => subscription id 
    $subscription = ChannelSubscription::where('channel_id', $channel_id)
        ->where('user_id', Auth::id())->with('channel')->get();
    // check if this user is subscripted to this channel
    if ($subscription->isEmpty()) {
        return $this->returnError('you are not subuscripted to this channel.', 403);
    }
    $subscription = $subscription->first();
    $message = SubscriptionMessage::create([
        'subscription_id' => $subscription->id,
        'message' => $messageText
    ]);
    $currentUser = User::where('id', Auth::id())->first();
    // 1. send it as event to all subscribers
    event(new NewMessageEvent(
        $subscription->channel->name,
        $subscription->channel->type,
        $messageText,
        $message->id,
        $channel_id,
        Carbon::parse($message->created_at)->format('h:i A'),
        Carbon::parse($message->created_at)->diffForHumans(),
        $currentUser
    ));
    // 2. save the status as received.
    // get all this channel subscription
    $subscriptions_id = ChannelSubscription::where('channel_id', $channel_id)
        ->where('user_id', '!=', $currentUser->id)->get()->pluck('id');
    // create status for all channel-subscription and this message as status default value is received.
    foreach ($subscriptions_id as $id) {
        MessageStatus::create([
            'message_id' => $message->id,
            'subscription_id' => $id,
        ]);
    }
})->middleware('auth');
Route::get('/', [AuthenticatedSessionController::class, 'findNextRoute'])->middleware('auth');
Route::post('authenticate_websocket', [ChatWebsocketController::class, 'authenticateUser'])->middleware('auth');


Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest:web', 'menu:admin');
Route::post('/login-store', [AuthenticatedSessionController::class, 'store'])->name('login.perform')->middleware('guest:web');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register-store', [RegisteredUserController::class, 'store'])->name('register.perform');
Route::group(['middleware' => ['auth']], function () {
    Route::post('/users/self/update', [AdminController::class, 'updateSelf'])->name('user.self.update');
    Route::post('/users/self/update-avatar', [AdminController::class, 'updateSelfAvatar'])->name('user.self.update.avatar');
    Route::post('/notification.read', [HomeController::class, 'readNotification'])->name('notification.read');
});

Route::group(['middleware' => ['auth', 'menu:coach', 'type:coach'], 'prefix' => 'coach'], function () {
    Route::get('home', [CoachController::class, 'home_view']);
    Route::get('/profile', [CoachController::class, 'coach_profile'])->name('coach.profile');
    Route::get('/trainees', [CoachController::class, 'trainnes_view'])->name('coach.trainees');
    Route::get('/timelines', [CoachController::class, 'timelines_view'])->name('coach.timelines.all');
    Route::get('/timelines/{id}', [CoachController::class, 'show_timeline_view'])->name('coach.timeline.show');
    Route::get('/timelines.new', [CoachController::class, 'new_timeline_view'])->name('coach.timelines.new');
    Route::get('/timelines.items/new/{id}', [CoachController::class, 'show_timeline_add_item_view'])->name('coach.timelines.items.new');
    Route::get('/chat/{trainee_id}', [CoachController::class, 'trainee_chat']);
    Route::get('chats', [CoachController::class, 'chat_view'])->name('chat');
    Route::get('/meals', [CoachController::class, 'show_all_meals'])->name('coach.meals.all');
    Route::get('/meals.new', [CoachController::class, 'add_new_meal'])->name('coach.meals.new');
    Route::get('/exercises', [CoachController::class, 'show_all_exercises'])->name('coach.exercises.all');
    Route::get('/exercises.new', [CoachController::class, 'new_exerciese_view'])->name('coach.exercises.new');
    
    Route::post('chats/load', [CoachController::class, 'loadChat'])->name('chat.load');
    Route::post('/timelines.new', [CoachController::class, 'new_timeline_store'])->name('coach.timelines.new.store');
    Route::post('/timelines.items/new', [CoachController::class, 'timeline_item_store'])->name('coach.timelines.item.new.store');
    Route::post('/timelines.items/update', [CoachController::class, 'timeline_item_update'])->name('coach.timelines.item.update');
    Route::post('/timelines.items/delete', [CoachController::class, 'timeline_item_delete'])->name('coach.timelines.item.delete');
    Route::post('/type.items/new', [CoachController::class, 'new_type_item'])->name('coach.item.type.new.store');
    Route::post('chats/message/read', [CoachController::class, 'readMessage'])->name('chat.message.read');
    Route::post('chats/message/new/read', [CoachController::class, 'readNewMessage'])->name('chat.message.new.read');
    Route::post('chats/message/new', [CoachController::class, 'newMessage'])->name('chat.message.new');
    Route::post('/meals.new', [CoachController::class, 'store_new_meal']);
    Route::post('/exercises.new', [CoachController::class, 'store_new_exercise']);
    
    Route::delete('/timelines', [CoachController::class, 'timeline_delete'])->name('coach.timelines.delete');
    Route::delete('/meal.del', [CoachController::class, 'meal_delete'])->name('coach.meal.delete');
});

Route::get('/sign-in-static', function () {
    return view('pages.sign-in-static');
})->name('sign-in-static');

Route::get('/profile-static', function () {
    return view('pages.profile-static');
})->name('profile-static');
Route::get('/sign-up-static', function () {
    return view('pages.sign-up-static');
})->name('sign-up-static');
Route::get('/profile', function () {
    return view('pages.profile');
})->name('profile');
Route::get('/page', function () {
    return view('pages.tables');
})->name('page')->middleware('menu:admin');
Route::get('/virtual-reality', function () {
    return view('pages.virtual-reality');
})->name('virtual-reality');
Route::get('/rtl', function () {
    return view('pages.rtl');
})->name('rtl')->middleware('menu:admin');
